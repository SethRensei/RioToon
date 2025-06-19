<?php

namespace Riotoon\Entity;

use Riotoon\Service\BuildErrors;

class User
{
    private ?int $u_id;
    private ?string $pseudo;
    private ?string $fullname;
    private ?string $email;
    private $roles = ['ROLE_USER'];
    private ?string $password;
    private ?string $profile_picture = null;
    private $created_at;

    /**
     * Get the value of u_id
     *
     * @return ?int
     */
    public function getId(): ?int {
        return $this->u_id;
    }

    /**
     * Get the value of pseudo
     *
     * @return ?string
     */
    public function getPseudo(): ?string {
        return $this->pseudo;
    }

    /**
     * Set the value of pseudo
     *
     * @param ?string $pseudo
     *
     * @return self
     */
    public function setPseudo(?string $pseudo): self {
        $pseudo = strtolower(clean($pseudo));
        if (!(strlen($pseudo) >= 5 && strlen($pseudo) <= 15))
            BuildErrors::setErrors('pseudo', 'Le pseudo doit contenir entre 5-15 caractères');
        if (preg_match('/[^\w]/', $pseudo))
            BuildErrors::setErrors('pseudo', "Pas de caractères spéciaux et d'espace sauf '_'");
        $this->pseudo = $pseudo;
        return $this;
    }

    /**
     * Get the value of fullname
     *
     * @return ?string
     */
    public function getFullname(): ?string {
        return $this->fullname;
    }

    /**
     * Set the value of fullname
     *
     * @param ?string $fullname
     *
     * @return self
     */
    public function setFullname(?string $fullname): self {
        $fullname = ucwords(clean($fullname));
        if (!(preg_match('/^[a-zA-Z\s]+$/', $fullname)))
            BuildErrors::setErrors('fullname', 'Ne contient que des lettres et espaces');
        $this->fullname = $fullname;
        return $this;
    }

    /**
     * Get the value of email
     *
     * @return ?string
     */
    public function getEmail(): ?string {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param ?string $email
     *
     * @return self
     */
    public function setEmail(?string $email): self {
        $email = strtolower(clean($email));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            BuildErrors::setErrors('email', 'Email invalid');
        $this->email = $email;
        return $this;
    }

    /**
     * Get the value of roles
     *
     * @return mixed
     */
    public function getRoles()
    {
        return json_encode($this->roles);
    }

    /**
     * Set the value of roles
     *
     * @param array $roles
     *
     * @return self
     */
    public function setRoles(array $roles): self
    {
        $roles[] = 'ROLE_USER';
        $this->roles = json_encode(array_unique($roles));
        return $this;
    }

    /**
     * Get the value of password
     *
     * @return ?string
     */
    public function getPassword(): ?string {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @param ?string $password
     *
     * @return self
     */
    public function setPassword(?string $password): self {
        $password = clean($password);
        if (!(strlen($password) >= 8 && strlen($password) <= 30))
            BuildErrors::setErrors('password', 'Mot de passe non conforme aux règles');
        if (!(preg_match('/[0-9]/', $password) && preg_match('/[A-Za-z]/i', $password)))
            BuildErrors::setErrors('password', 'Mot de passe non conforme aux règles');
        if (!preg_match('/[-~&?!^*#£%µ¤«§<>_@=$€»+]/', $password))
            BuildErrors::setErrors('password', 'Mot de passe non conforme aux règles');
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }

    /**
     * Get the value of profile_picture
     *
     * @return string|null
     */
    public function getProfile(): ?string
    {
        return $this->profile_picture;
    }

    /**
     * Set the value of profile_picture
     *
     * @param string $profile
     *
     * @return self
     */
    public function setProfile(string $profile): self
    {
        $this->profile_picture = $profile;

        return $this;
    }

    /**
     * Get the value of modified_at
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }
}
