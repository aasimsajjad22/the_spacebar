<?php


namespace App\Form\DataTransformer;


use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EmailToUserTransformer implements DataTransformerInterface
{

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var callable
     */
    private $finderCallable;

    public function __construct(UserRepository $userRepository, callable $finderCallable)
    {
        $this->userRepository = $userRepository;
        $this->finderCallable = $finderCallable;
    }

    public function transform($value)
    {
        if(null == $value) {
            return '';
        }
        if(!$value instanceof User) {
            throw new \LogicException('The UserSelectTextType can only be used with User Objects');
        }
        return $value->getEmail();
    }

    public function reverseTransform($value)
    {
        if(!$value) {
            return;
        }
        $callback = $this->finderCallable;
        $user = $callback($this->userRepository, $value);
        if(!$user) {
            throw new TransformationFailedException(sprintf('No user found with email "%s"', $value));
        }
        return $user;
    }


}