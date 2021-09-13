<?php namespace Classes;

use Models\User;
use Phalcon\Di;
use Phalcon\Security\JWT\Builder;
use Phalcon\Security\JWT\Signer\Hmac;
use Phalcon\Security\JWT\Token\Parser;
use Phalcon\Security\JWT\Validator;

class Auth {

    const JWT_PASSWORD = 'QcMpZ&b&mo3TPsPk668J6QH8JA$&U&m2';

    public static function getToken(User $user) {
        $now        = new \DateTimeImmutable();
        return (new Builder(new Hmac()))
            ->setAudience('http://localhost')
            ->setContentType('application/json')
            ->setId($user->username)
            ->setExpirationTime($now->modify('+4 hour')->getTimestamp())
            ->setNotBefore($now->modify('-1 minute')->getTimestamp())
            ->setIssuedAt($now->getTimestamp())
            ->setIssuer('http://localhost')
            ->setSubject('Houses API')
            ->setPassphrase(self::JWT_PASSWORD)
            ->getToken()->getToken();
    }

    public static function verifyToken(string $token) {
        $now        = new \DateTimeImmutable();
        try {
            $token = (new Parser())->parse($token);
            $user = User::findFirstByUsername($token->getClaims()->getPayload()['jti']);
            Di::getDefault()->setShared('user', $user);
            if (!$user) {
                return false;
            }
            (new Validator($token, 100))
                ->validateAudience('http://localhost')
                ->validateExpiration($now->getTimestamp())
                ->validateIssuedAt($now->getTimestamp())
                ->validateIssuer('http://localhost')
                ->validateNotBefore($now->modify('-1 minute')->getTimestamp())
                ->validateSignature(new Hmac(), self::JWT_PASSWORD);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

}