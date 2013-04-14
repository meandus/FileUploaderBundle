# QoopLmao FileUploadBundle

## Installation

Installing this bundle can be done through these simple steps:

1. Add the bundle to your project as a composer dependency:
```javascript
// composer.json
{
        // ...
        require: {
            // ...
            "qooplmao/file-uploader-bundle": "dev-master"
        }
}
```

2. Update your composer installation:
```shell
php composer.phar update qooplmao/file-uploader-bundle
````

3. Add the bundle to your application kernel:
```php
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

4. Create your Upload class (doctrine)

```
<?php

namespace Access\FileUploaderBundle\Entity;

use QoopLmao\FileUploaderBundle\Entity\Upload as BaseUpload;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="access_upload")
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
    }

    public function getId()
    {
        return $this->id;
    }
}
```

5. Configure the QoopLmaoFileUploaderBundle

```
qoop_lmao_file_uploader:
    upload_class: Acme\FileUploaderBundle\Entity\Upload
```

6. Import the QoopLmaoFileUploaderBundle routing

```
qoop_lmao_file_uploader:
    resource: "@QoopLmaoFileUploaderBundle/Resources/config/routing.yml"
    prefix:   /uploader/
```

7. Update the database schema

```
app/console doctrine:schema:update --force
```
