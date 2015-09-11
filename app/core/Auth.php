<?php

class Auth
{
    private $siteKey;
    private $userBrowser;
    private $loginString;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var User|false
     */
    private $user;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
        $this->siteKey = '12d3f2df45r';
        // Get the user-agent string of the user.
        $this->userBrowser = $_SERVER['HTTP_USER_AGENT'];
        $this->secureSessionStart();
        $this->loginString = @$_SESSION['loginString'];
        $this->user = false;
    }

    /**
     * @return User|false
     */
    public function getUser()
    {
        if (isset($_SESSION['userId'], $_SESSION['username'], $_SESSION['loginString'])) {
            $userId = $_SESSION['userId'];
            /** @var User $user */
            $user = DB::getObjectByID('User', $userId);
            if ($user) {
                $loginCheck = hash('sha512', $user->Password . $this->userBrowser);
                $this->user = $loginCheck == $this->loginString ? $user : false;
            }
        }
        return $this->user ?: false;
    }

    public function canAccessPage() {
        if (!$this->getUser()) {
            foreach($this->registry->config->accessControl as $pattern) {
                if (preg_match($pattern, $this->registry->request->getUrl())) {
                    return false;
                }
            }
        }
        return true;
    }

    protected function secureSessionStart()
    {
        $sessionName = $this->siteKey;   // Set a custom session name
        $secure = false;
        // This stops JavaScript being able to access the session id.
        $httpOnly = true;
        // Forces sessions to only use cookies.
        if (ini_set('session.use_only_cookies', 1) === FALSE) {
            header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
            exit();
        }
        // Gets current cookies params.
        $cookieParams = session_get_cookie_params();
        session_set_cookie_params($cookieParams["lifetime"],
            $cookieParams["path"],
            $cookieParams["domain"],
            $secure,
            $httpOnly);
        // Sets the session name to the one set above.
        session_name($sessionName);
        session_start();            // Start the PHP session
        session_regenerate_id(true);// regenerated the session, delete the old one.
    }

    public function login($email, $plainPassword)
    {
        /** @var User $user */
        $user = DB::getObjectBy('User', array('Email' => $email));
        if ($user) {
            // hash the password with the unique salt.
            $password = hash('sha512', $plainPassword . $user->Salt);
            // If the user exists we check if the account is locked
            // from too many login attempts
            if ($this->checkBrute($user) == true) {
                // Account is locked
                return false;
            } else {
                $userId = $user->getId();
                if ($user->Password == $password) {
                    // XSS protection as we might print this value
                    $_SESSION['userId'] = $userId;
                    $_SESSION['username'] = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $user->getUsername());
                    $this->loginString = hash('sha512', $password . $this->userBrowser);
                    $_SESSION['loginString'] = $this->loginString;
                    $this->user = $user;
                    return true;
                } else {
                    $now = time();
                    $stmt = DB::$pdo->prepare("INSERT INTO login_attempts SET user_id = :userId, time = :t");
                    $stmt->bindParam('userId', $userId);
                    $stmt->bindParam('t', $now);
                    if (!$stmt->execute()) {
                        throw new Exception(implode($stmt->errorInfo()));
                    }

                    return false;
                }
            }
        }
        // No user exists.
        return false;
    }

    public function logout()
    {
        $this->secureSessionStart();
        // Unset all session values
        $_SESSION = array();
        // get session parameters
        $params = session_get_cookie_params();
        // Delete the actual cookie.
        setcookie(session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
        // Destroy session
        session_destroy();
        header('Location: ' . BASE_URL);
    }

    private function checkBrute(User $user)
    {
        // Get timestamp of current time
        $now = time();
        // All login attempts are counted from the past 2 hours.
        $validAttempts = $now - (2 * 60 * 60);
        $userId = $user->getId();
        if ($stmt = DB::$pdo->prepare("SELECT time FROM login_attempts WHERE user_id = :id AND time > :attempts")) {
            $stmt->bindParam(':id', $userId);
            $stmt->bindParam(':attempts', $validAttempts);
            // Execute the prepared query.
            if (!$stmt->execute()) {
                throw new Exception(implode($stmt->errorInfo()));
            }
            // If there have been more than 5 failed logins
            if ($stmt->rowCount() > 5) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }
}
