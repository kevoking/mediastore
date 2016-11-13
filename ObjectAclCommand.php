<?php
/**
 * Copyright 2015 Google Inc. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Google\Cloud\Samples\Storage;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command line utility to manage Cloud Storage ACLs.
 *
 * Usage: php storage.php object-acl
 */
class ObjectAclCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('object-acl')
            ->setDescription('Manage the ACL for Cloud Storage objects')
            ->setHelp(<<<EOF
The <info>%command.name%</info> command manages Cloud Storage ACL.

    <info>php %command.full_name% --help</info>

EOF
            )
            ->addArgument(
                'bucket',
                InputArgument::REQUIRED,
                'The Cloud Storage bucket name'
            )
            ->addArgument(
                'object',
                InputArgument::REQUIRED,
                'The Cloud Storage object name'
            )
            ->addOption(
                'entity',
                null,
                InputOption::VALUE_REQUIRED,
                'Add or filter by a user'
            )
            ->addOption(
                'role',
                null,
                InputOption::VALUE_REQUIRED,
                'One of OWNER, READER, or WRITER',
                'READER'
            )
            ->addOption(
                'create',
                null,
                InputOption::VALUE_NONE,
                'Create an ACL for the supplied user'
            )
            ->addOption(
                'delete',
                null,
                InputOption::VALUE_NONE,
                'Remove a user from the ACL'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bucketName = $input->getArgument('bucket');
        $entity = $input->getOption('entity');
        $role = $input->getOption('role');
        $objectName = $input->getArgument('object');
        if ($entity) {
            if ($input->getOption('create')) {
                add_object_acl($bucketName, $objectName, $entity, $role);
            } elseif ($input->getOption('delete')) {
                delete_object_acl($bucketName, $objectName, $entity);
            } else {
                get_object_acl_for_entity($bucketName, $objectName, $entity);
            }
        } else {
            get_object_acl($bucketName, $objectName);
        }
    }
}
