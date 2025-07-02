<?php
require_once("Model.php");

class User extends Model
{

    private string $fname;
    private string $lname;
    private string $phone;
    private string $email;
    private string $password;
    private int $payment;

    protected static string $table = "users";

    public function __construct(array $data)
    {
        $this->fname = $data["fname"];
        $this->lname = $data["lname"];
        $this->phone = $data["phone"];
        $this->email = $data["email"];
        $this->password = $data["password"];
        $this->payment = $data["payment"];
    }

    public function  getFname(): string
    {
        return $this->fname;
    }

    public function getLname(): string
    {
        return $this->lname;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setFname(string $fname)
    {
        $this->fname = $fname;
    }
    public function setLname(string $lname)
    {
        $this->lname = $lname;
    }
    public function setPhone(string $phone)
    {
        $this->phone = $phone;
    }
    public function setEmail(string $email)
    {
        $this->email = $email;
    }
    public function setPassword(string $password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function toArray()
    {
        return [
            'fname' => $this->fname,
            'lname' => $this->lname,
            'phone' => $this->phone,
            'email' => $this->email,
            'password' => password_hash($this->password, PASSWORD_DEFAULT),
            'payment' => $this->payment,
        ];
    }

    public function emailOrPhoneExists(mysqli $mysqli): bool
    {
        $sql = "SELECT * FROM users WHERE email = ? OR phone = ?";
        $check = $mysqli->prepare($sql);
        $check->bind_param("ss", $this->email, $this->phone);
        $check->execute();
        $existingUser = $check->fetch();
        $check->close();
        // If the query returns a result, it means the email or phone already exists
        if ($existingUser) {
            return true;
        }
        // If no result is returned, it means the email or phone does not exist
        return false;
    }
}
