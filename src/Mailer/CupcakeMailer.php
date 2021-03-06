<?php
declare(strict_types=1);

namespace Cupcake\Mailer;

use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\Mailer\Email;
use Cake\Mailer\Exception\MissingActionException;
use Cake\Mailer\Mailer;

/**
 * Class CupcakeMailer
 *
 * @package Cupcake\Mailer
 * @deprecated Use CakePHP's built-in mailer instead
 */
class CupcakeMailer extends Mailer
{
    /**
     * Sends email.
     *
     * @param string $action The name of the mailer action to trigger.
     * @param array $args Arguments to pass to the triggered mailer action.
     * @param array $headers Headers to set.
     * @return array
     * @throws \Cake\Mailer\Exception\MissingActionException
     * @throws \BadMethodCallException
     * @deprecated Use MailmanTransport instead
     */
    public function send(?string $action = null, array $args = [], array $headers = []): array
    {
        if (!method_exists($this, $action)) {
            throw new MissingActionException([
                'mailer' => $this->getName() . 'Mailer',
                'action' => $action,
            ]);
        }

        $this->_email->setHeaders($headers);
        if (!$this->_email->viewBuilder()->getTemplate()) {
            $this->_email->viewBuilder()->setTemplate($action);
        }

        call_user_func_array([$this, $action], $args);
        $result = $this->_send($this->_email);

        $this->reset();

        return $result;
    }

    /**
     * @param \Cake\Mailer\Email $email
     * @return array
     * @deprecated
     */
    public function sendEmail(Email $email, $content = null, $throwExceptions = false)
    {
        return $this->_send($email, $content, $throwExceptions);
    }

    /**
     * Send email
     *
     * @param \Cake\Mailer\Email $email
     * @param bool $content
     * @return array
     * @throws \Exception
     * @deprecated Use MailmanTransport instead
     */
    protected function _send(Email $email, $content = null, $throwExceptions = false)
    {
        $result = null;
        $exception = null;
        try {
            $event = EventManager::instance()->dispatch(new Event('Email.beforeSend', $email));
            //@TODO Stop email sending when event is stopped / result is FALSE

            $result = $email->send($content);
        } catch (\Exception $ex) {
            $result = ['error' => $ex->getMessage()];
            $exception = $ex;
        } finally {
            $event = EventManager::instance()->dispatch(new Event('Email.afterSend', $email, $result));
        }

        if ($throwExceptions) {
            throw $exception;
        }

        return $result;
    }
}
