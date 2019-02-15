<?php declare(strict_types=1);

const CHECK_PATHS_DIR = __DIR__ . '/check_paths';
const JOB_TEMPLATE_DIR = __DIR__ . '/templates/jobs';
$jobXml = file_get_contents(CHECK_PATHS_DIR . '/job_config.xml');

function getCheckPathsFromFile($checkPathFile) {
    return array_map('trim', explode(PHP_EOL, trim(file_get_contents(CHECK_PATHS_DIR . '/' . $checkPathFile))));
}
function ensureJobDirectory(string $jobName): void {
    $jobDirectory = JOB_TEMPLATE_DIR . '/' . $jobName;
    if (!is_dir($jobDirectory)) {
        mkdir($jobDirectory);
    }
}
function writeJobConfig($jobName, $checkPath, $projectName) {
    global $jobXml;
    $jobDirectory = JOB_TEMPLATE_DIR . '/' . $jobName;
    $jobTemplate = str_replace(
        ['__CHECK_PATH__', '__CHECK_PROJECT__'],
         [$checkPath, $projectName],
         $jobXml
    );
    file_put_contents($jobDirectory . '/config.xml', $jobTemplate);
}

// Generate Drupal core jobs.
$checkPaths = getCheckPathsFromFile('core');
foreach ($checkPaths as $checkPath) {
    if ($checkPath == 'core/includes') {
        $jobName = 'core_legacy_includes';
    } elseif ($checkPath == 'core/lib/Drupal/Component') {
        $jobName = 'core_namespace_component';
    } elseif ($checkPath == 'core/lib/Drupal/Core') {
        $jobName = 'core_namespace_core';
    } else {
        $splitPaths = explode('/', $checkPath);
        $jobName = 'core_' . end($splitPaths);
    }

    ensureJobDirectory($jobName);
    writeJobConfig($jobName, $checkPath, 'core');
}

// Generate contrib module jobs
$moduleNames = getCheckPathsFromFile('modules');
foreach ($moduleNames as $moduleName) {
    $jobName = 'contrib_' . $moduleName;

    ensureJobDirectory($jobName);
    writeJobConfig($jobName, 'modules/contrib/' . $moduleName, $moduleName);
}

// Generate contrib themes jobs
$moduleNames = getCheckPathsFromFile('themes');
foreach ($moduleNames as $moduleName) {
    $jobName = 'contrib_' . $moduleName;

    ensureJobDirectory($jobName);
    writeJobConfig($jobName, 'themes/contrib/' . $moduleName, $moduleName);
}

// Generate contrib profiles jobs
$moduleNames = getCheckPathsFromFile('profiles');
foreach ($moduleNames as $moduleName) {
    $jobName = 'contrib_' . $moduleName;

    ensureJobDirectory($jobName);
    writeJobConfig($jobName, 'profiles/contrib/' . $moduleName, $moduleName);
}
