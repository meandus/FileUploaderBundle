# QoopLmao FileUploadBundle

## Installation

Installing this bundle can be done through these simple steps:

Step 1. Add the bundle to your project as a composer dependency:
``` javascript
// composer.json
{
        // ...
        require: {
            // ...
            "qooplmao/file-uploader-bundle": "dev-master"
        }
}
```

Step 2. Update your composer installation:
``` shell
php composer.phar update qooplmao/file-uploader-bundle
````

Step 3. Add the bundle to your application kernel:
``` php
// application/ApplicationKernel.php
public function registerBundles()
{
        // ...
        $bundle = array(
            // ...
            new new QoopLmao\FileUploaderBundle\QoopLmaoFileUploaderBundle(),
        );
        // ...

        return $bundles;
}
```

Step 4. Create your Upload class (doctrine):
``` php
<?php
// src/Acme/FileUploaderBundle/Entity/Upload

namespace Acme\FileUploaderBundle\Entity;

use QoopLmao\FileUploaderBundle\Entity\Upload as BaseUpload;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="qooplmao_upload")
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

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
}
```

Step 5. Configure the QoopLmaoFileUploaderBundle:
``` yaml
qoop_lmao_file_uploader:
    upload_class: Acme\FileUploaderBundle\Entity\Upload
```

Step 6. Import the QoopLmaoFileUploaderBundle routing:
``` yaml
qoop_lmao_file_uploader:
    resource: "@QoopLmaoFileUploaderBundle/Resources/config/routing.yml"
    prefix:   /uploader/
```

Step 7. Update the database schema:
``` shell
app/console doctrine:schema:update --force
```
