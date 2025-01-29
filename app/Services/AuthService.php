<?php

namespace App\Services;

use App\Contracts\Services\AuthInterface;
use App\Http\Resources\UserResource;
use App\Services\TokenService;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Exceptions\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class AuthService implements AuthInterface
{
    /**
     * Token işlemleri için kullanılan servis tanımlanır.
     * @var TokenService
     */
    protected TokenService $tokenService;

    /**
     * Kullanıcı veritabanı işlemleri için kullanılan repository tanımlanır.
     * @var UserRepositoryInterface
     */
    protected UserRepositoryInterface $userRepository;

    /**
     * AuthService sınıfının yapıcı fonksiyonu.
     * @param \App\Services\TokenService $tokenService
     * @param \App\Contracts\Repositories\UserRepositoryInterface $userRepository
     */
    public function __construct(TokenService $tokenService, UserRepositoryInterface $userRepository)
    {
        $this->tokenService = $tokenService;
        $this->userRepository = $userRepository;
    }

    /**
     * Yeni bir kullanıcı kaydı oluşturur.
     * Oluşturulan kullanıcı kaydı için token oluşturulur.
     * Kullanıcı ve token bilgileri geri döner.
     * @param array $data
     * @return JsonResponse
     */
    public function register(array $userData): JsonResponse
    {
        $newUser = $this->userRepository->createUser($userData);
        $newUserToken = $this->tokenService->generateToken($newUser);
        $expiresIn = $this->tokenService->generateTokenExpiresIn();
        return response()->json(['token' => $newUserToken, 'expires_in' => $expiresIn], 201);
    }

    /**
     * Kimlik bilgileri doğrulama işlemi başlar.
     * Doğrulama esnasında hata oluşursa istisna fırlatır.
     * Oturum açan kullanıcıya rol bilgisi içeren bir token oluşturulur.
     * Kullanıcı ve token bilgileri geri döner.
     * @param array $credentials
     * @throws \App\Exceptions\AuthenticationException
     * @return JsonResponse
     */
    public function login(array $userCredentials): JsonResponse
    {
        if (!Auth::attempt($userCredentials)) {
            throw new AuthenticationException("Kimlik doğrulama hatası.", 401);
        }
        $userData = $this->tokenService->getAuthenticatedUser();
        $loginUserToken = $this->tokenService->generateTokenWithPayload($userData, ["role" => $userData->role]);
        $expiresIn = $this->tokenService->generateTokenExpiresIn();
        return response()->json(['token' => $loginUserToken, 'expires_in' => $expiresIn], 200);
    }

    /**
     * Oturumu açık olan kullanıcının token'ı geçersiz kılınır.
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $this->tokenService->invalidateToken();
        return response()->json(['success' => true, 'message' => "Oturum sonlandırma isteği başarıyla tamamlandı."], 200);
    }

    /**
     * Mevcut tokenı sonlandırır ve yeni token oluşturur.
     * Yenilenen token bilgileri geri döner.
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        $newValidToken = $this->tokenService->refreshToken();
        $expiresIn = $this->tokenService->generateTokenExpiresIn();
        return response()->json(['token' => $newValidToken, 'expires_in' => $expiresIn], 200);
    }

    /**
     * Oturumu devam eden kullanıcı bilgilerini getirir.
     * @return JsonResponse
     */
    public function getAuthenticatedUser(): JsonResponse
    {
        $userData = $this->tokenService->getAuthenticatedUser();
        $userData = new UserResource($userData);
        return response()->json($userData, 200);
    }
}
