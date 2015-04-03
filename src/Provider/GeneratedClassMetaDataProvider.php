<?php
/**
 * File GeneratedClassMetaDataProvider.php
 */

namespace Tebru\Retrofit\Provider;

/**
 * Class GeneratedClassMetaDataProvider
 *
 * This class will use a ClassMetaDataProvider instance and format the data
 * for use with a generated file.
 *
 * @author Nate Brunette <n@tebru.net>
 */
class GeneratedClassMetaDataProvider
{
    /**
     * Namespace prefix for psr4
     */
    const NAMESPACE_PREFIX = 'Tebru\Retrofit\Service';

    /**
     * Instance of the class meta data provider
     *
     * @var ClassMetaDataProvider
     */
    private $classMetaDataProvider;

    /**
     * Constructor
     *
     * @param ClassMetaDataProvider $classMetaDataProvider
     */
    public function __construct(ClassMetaDataProvider $classMetaDataProvider)
    {
        $this->classMetaDataProvider = $classMetaDataProvider;
    }

    /**
     * Get the full namespace name with prefix
     *
     * @return string
     */
    public function getNamespaceFull()
    {
        return self::NAMESPACE_PREFIX . '\\' . $this->getNamespacePsr4();
    }

    /**
     * Get the namespace name for psr4 directory structure
     *
     * @return string
     */
    public function getNamespacePsr4()
    {
        return $this->classMetaDataProvider->getNamespace();
    }

    /**
     * Get the actual filename without the full path
     *
     * @return string
     */
    public function getFilenameShort()
    {
        $filename = $this->classMetaDataProvider->getFilename();
        $filename = $this->getShortFilename($filename);

        return $filename;
    }

    /**
     * Get the full filename including the full path
     *
     * @return string
     */
    public function getFilenameFull()
    {
        return $this->getFilePath() . DIRECTORY_SEPARATOR . $this->getFilenameShort();
    }

    /**
     * Get the path based on namespace
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->namespaceToPath($this->getNamespacePsr4());
    }

    /**
     * Take a namespace and create a file path
     *
     * @param $classNamespace
     * @return array|string
     */
    private function namespaceToPath($classNamespace)
    {
        $path = explode('\\', $classNamespace);
        $path = implode(DIRECTORY_SEPARATOR, $path);

        return $path;
    }

    /**
     * Get the end of the filename from file path
     *
     * @param $filename
     * @return string
     */
    private function getShortFilename($filename)
    {
        $filename = explode(DIRECTORY_SEPARATOR, $filename);
        $filename = array_pop($filename);

        return $filename;
    }
}
