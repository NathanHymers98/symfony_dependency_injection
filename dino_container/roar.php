<?php

namespace Dino\Play;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

$start = microtime(true);

require __DIR__.'/../vendor/autoload.php';

$cachedContainer = __DIR__.'/cached_container.php'; // Creating the cached_container file under the variable $cachedContainer
if (!file_exists($cachedContainer)) { // if the file $cachedContainer doesn't exist, then run the code below to dump the cacheContainer file
    $container = new ContainerBuilder();
    $container->setParameter('root_dir', __DIR__); // Setting the custom parameter 'root_dir' to __DIR__ which is the root directory. Doing this means that we can use the parameter 'root_dir' anywhere when we're building the container

    $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/config')); // Creating a new loader object from the dependency injection component.
                                                                                    // It takes two arguments, the container and a new file locator which looks for files in a config directory.
    $loader->load('services.yml'); // Making the loader able to read yaml files. It tells the container to go find service definitions in the services.yml file

    $container->compile(); // Compiling the container object
    $dumper = new PhpDumper($container); // After compiling, I am creating a new PhpDumper object called $dumper and passing it the $container argument.
    file_put_contents(__DIR__ . '/cached_container.php', $dumper->dump()); // This will take the metadata and cache it to a file
}
require $cachedContainer; // If the cache container file does exist then require it
$container = new \ProjectServiceContainer(); // This is the class name used in the cache file

runApp($container);

$elapsed = round((microtime(true) - $start) * 1000);
$container->get('logger')->debug('Elapsed Time: '.$elapsed.'ms');

function runApp(ContainerInterface $container)
{
    $container->get('logger')->info('ROOOOAR');
}
