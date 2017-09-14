<?php

namespace Deployer;

// Use the Yaml component to read the data.
use Symfony\Component\Yaml\Yaml;

// This is loaded in by the deployer.phar and is accessible.
use Deployer\Task\Context;

// Detect the root dir.
$projectRootDir = dirname(dirname(dirname(dirname(__DIR__))));

/*
 * Since the information is contained in the .properties/*.yml
 * Note that these should have been provisioned by the
 * "phing properties:write-full"
 */
$files = [
  'dir',
  'project',
  'server',
  'bin',
];

$workflowProperties = [];

foreach($files as $file) {
  $fullPath = $projectRootDir . '/properties/' . $file . '.yml';
  if (!file_exists($fullPath)) {
    throw new \Exception(sprintf('Required file %s not found, have you ensured that the properties were completed before starting the deploy. Has phing properties:write-full been run?', $fullPath));
  }
  $workflowProperties[$file] =  Yaml::parse(file_get_contents($fullPath));
}

/*
 * Add various items to the active config.
 */
// This needs to be made relative ...
$relativeWebDir = str_replace($workflowProperties['project']['basePath'] . '/', '', $workflowProperties['dir']['web']['root']);
set('dir_web_relative', $relativeWebDir);

$relativePhingDir = str_replace($workflowProperties['project']['basePath'] . '/', '', $workflowProperties['bin']['phing']);
set('bin_phing', $relativePhingDir);

// used by some scripts to generate a better absolute url
set('local_workspace', $workflowProperties['project']['basePath']);

/*
 * Generate server instances out of the data contained in the server.yml file.
 * This is a more custom implementation to make the system more uniform.
 */
foreach ($workflowProperties['server'] as $serverKey => $serverData) {
  server($serverKey, $serverData['host'])
    ->user($serverData['user'])
    ->set('deploy_path', $serverData['root'])
    ->stage($serverData['stage'])
    ->identityFile()
    // Context to be activated.
    ->set('env', $serverData['env'])
    ->set('site', $serverData['site'])
    ->set('stage', $serverData['stage'])
    ->set('root', $serverData['root'])
    ->set('shared_dirs', [
      $relativeWebDir . '/sites/' . $serverData['site'] . '/files',
    ])
    ->set('writable_dirs', [
      $relativeWebDir . '/sites/' . $serverData['site'] . '/files',
    ])
    // The .env file contains DB credentials etc specific for the environment.
    ->set('shared_files', [
      '.env'
    ]);
}