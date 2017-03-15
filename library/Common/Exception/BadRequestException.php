<?php
namespace Common\Exception;

class BadRequestException extends \Exception
{
    /**
     * @var null
     */
    private $detail;

    /**
     * BadRequestException constructor.
     * @param string          $message Error message
     * @param int             $code Error code
     * @param \Exception|null $previous Previous exception
     * @param array           $detail Array of validation errors
     */
    public function __construct($message, $code = 0, \Exception $previous = null, array $detail = [])
    {
        parent::__construct($message, $code, $previous);
        $this->detail = $detail;
    }

    /**
     * @return null
     */
    public function getDetail()
    {
        return $this->detail;
    }
}
