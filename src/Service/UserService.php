<?php
namespace App\Service;
use App\Entity\User;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token\Builder;
use DateTimeImmutable;
use App\Repository\UserRepository;
use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;
use Lcobucci\JWT\UnencryptedToken;

class UserService implements UserServiceInterface
{

    private UserRepository $userRepository;
    public function __construct(
                 UserRepository $userRepository
            ) {
                $this->userRepository = $userRepository;
            }

    public function getToken(User $user): string
    {
        $tokenBuilder = (new Builder(new JoseEncoder(), ChainedFormatter::default()));
        $algorithm = new Sha256();
        $signingKey = InMemory::plainText(random_bytes(32));
        $now   = new DateTimeImmutable();
        $token = $tokenBuilder
            // Configures the issuer (iss claim)
            ->issuedBy('http://localhost:8000')
            // Configures the audience (aud claim)
            ->permittedFor('http://localhost:8000')
            // Configures the id (jti claim)
            ->identifiedBy(hash('sha1', $user->getEmail()))
            // Configures the time that the token was issue (iat claim)
            ->issuedAt($now)
            // Configures the time that the token can be used (nbf claim)
            ->canOnlyBeUsedAfter($now->modify('+1 minute'))
            // Configures the expiration time of the token (exp claim)
            ->expiresAt($now->modify('+2 hour'))
            // Configures claims
            ->withClaim('uid', $user->getId())
            ->withClaim('email', $user->getEmail())
            // Builds a new token
            ->getToken($algorithm, $signingKey)
        ;
        return $token->toString();
    }

    public function parseToken(string $token)
    {
        $parser = new Parser(new JoseEncoder());
        try {
            return $parser->parse($token);
        } catch (CannotDecodeContent | InvalidTokenStructure | UnsupportedHeaderFound $e) {
            echo 'Oh no, an error: ' . $e->getMessage();
        }
        assert($token instanceof UnencryptedToken);
    }

    public function findOneByEmail(string $token)
    {
        $tokenParse = $this->parseToken($token);
        if (null !== $tokenParse) {
            $user = $this->userRepository->findOneByEmail($tokenParse->claims()->get('email'));
            return null !== $user ? $user->getEmail() : false;
        }
        return false;
    }
}