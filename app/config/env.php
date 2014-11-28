<?php

$mongodb_url = getenv('MONGODB_URL') ?: getenv('MONGOHQ_URL') ?: 'mongodb://localhost:27017/raffle';
$container->setParameter('env.mongodb_url', $mongodb_url);
$container->setParameter('env.mongodb_database', substr(parse_url($mongodb_url, PHP_URL_PATH), 1));

$container->setParameter('env.admin_password', getenv('ADMIN_PASSWORD')?:sha1(microtime(true)+mt_rand()));

$container->setParameter('env.getfeedback_api_token', getenv('GETFEEDBACK_API_TOKEN'));
?>