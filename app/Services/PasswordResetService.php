<?php
namespace App\Services;

use App\Interfaces\PasswordResetRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use Carbon\Carbon;
use Exception;

class PasswordResetService
{
    protected $tokenRepo;
    protected $userRepo;

    // token TTL in minutes (e.g., 60 minutes)
    protected $ttlMinutes = 60;

    public function __construct(
        PasswordResetRepositoryInterface $tokenRepo,
        UserRepositoryInterface $userRepo
    ) {
        $this->tokenRepo = $tokenRepo;
        $this->userRepo = $userRepo;
    }

    /**
     * Create token, store hashed token, and send email with raw token.
     */


    //  public function sendResetLink(string $email)
    //  {
    //      MailConfigHelper::loadDynamicMailConfig(true); // force reload
     
     
    //      Mail::mailer('smtp')
    //          ->to($email)
    //          ->send(new ResetPasswordMail($rawToken, $email));
     
    //      return true;
    //  }

     
    public function sendResetLink(string $email)
    {
        // ensure user exists
        $user = $this->userRepo->findByEmail($email);
        if (!$user) {
            // do not reveal; return true for non-existing email for security
            return true;
        }

        // create raw token
        $rawToken = Str::random(60);
        $tokenHash = hash('sha256', $rawToken);

        // store hashed token
        $this->tokenRepo->createToken($email, $tokenHash);

        // send mail (raw token included)
        Mail::to($email)->send(new ResetPasswordMail($rawToken, $email));

        return true;
    }

    /**
     * Validate token and reset password
     */
    public function resetPassword(string $email, string $rawToken, string $newPassword)
    {
        $record = $this->tokenRepo->findByEmail($email);
        if (!$record) {
            throw new Exception('Invalid or expired token.');
        }

        // check TTL
        $createdAt = Carbon::parse($record->created_at);
        if ($createdAt->diffInMinutes(Carbon::now()) > $this->ttlMinutes) {
            // expired
            $this->tokenRepo->deleteByEmail($email);
            throw new Exception('Token expired.');
        }

        // compare hash
        $tokenHash = hash('sha256', $rawToken);
        if (!hash_equals($tokenHash, $record->token)) {
            throw new Exception('Invalid token.');
        }

        // update user's password (via user repository)
        $this->userRepo->updatePasswordByEmail($email, $newPassword);

        // delete token after successful reset
        $this->tokenRepo->deleteByEmail($email);

        return true;
    }
}
