# QoopLmao FileUploadBundle

## Combine with FOSUserBundle

Combining this bundle with FOSUserBundle can be done through these simple steps:

Step 1. Update your User class:

Forexample if you are using ``Acme\UserBundle\Entity\User.php``
``` php
// src/Acme/UserBundle/Entity/User.php

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use QoopLmao\FileUploaderBundle\Model\UserInterface as QoopLmaoFileUploaderUserInterface;

/**
 * @ORM\Entity
 */
class User extends BaseUser implements QoopLmaoFileUploaderUserInterface
{
    // your code here
}
```

Step 2. Update your User concrete class:
``` php
// src/Acme/UserBundle/Entity/User.php

namespace Acme\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use QoopLmao\FileUploaderBundle\Model\UserInterface as QoopLmaoFileUploaderUserInterface;

/**
 * @ORM\Entity
 */
class User extends BaseUser implements QoopLmaoFileUploaderUserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="\Access\FileUploaderBundle\Entity\Upload", mappedBy="user")
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $uploads;

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Add gallery_cover_images
     *
     * @param \Acme\FileUploaderBundle\Entity\Upload $upload
     * @return User
     */
    public function addUpload(\Acme\FileUploaderBundle\Entity\Upload $upload)
    {
        $this->uploads[] = $upload;

        return $this;
    }

    /**
     * Remove upload
     *
     * @param \Acme\FileUploaderBundle\Entity\Upload $upload
     */
    public function removeUpload(\Acme\FileUploaderBundle\Entity\Upload $upload)
    {
        $this->uploads->removeElement($upload);
    }

    /**
     * Get uploads
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUploads()
    {
        return $this->uploads;
    }
}
````

Step 3. Update your Upload concrete class:
``` php
// src/Acme/FileUploaderBundle/Entity/Upload.php

namespace Acme\FileUploaderBundle\Entity;

use QoopLmao\FileUploaderBundle\Entity\Upload as BaseUpload;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Upload extends BaseUpload
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="\Access\UserBundle\Entity\User", inversedBy="uploads")
     */
    protected $user;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Set user
     *
     * @param \Acme\UserBundle\Entity\User $user
     * @return Upload
     */
    public function setUser(\Acme\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Acme\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
```

Step 4. Update mapping (and rename as necessary):
``` shell
app/console doctrine:mapping:convert --namespace="Acme\FileUploaderBundle" yml
                                                        src/Acme/FileUploaderBundle/Resources/config/doctrine
app/console doctrine:mapping:convert --namespace="Acme\UserBundle" yml
                                                        src/Acme/UserBundle/Resources/config/doctrine
```

Step 5. Update the database schema:
``` shell
app/console doctrine:schema:update --force
```
