<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 25.07.16
 * Time: 13:11
 */

namespace Dopamedia\StateMachine\Console\Command;


use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DrawGraphCommand extends \Symfony\Component\Console\Command\Command
{
    const INPUT_KEY_PROCESS_NAME = 'name';
    const INPUT_KEY_FILE_NAME = 'filename';
    const INPUT_KEY_HIGHLIGHT_STATE = 'highlight_state';
    const INPUT_KEY_FORMAT = 'format';
    const INPUT_KEY_FONT_SIZE = 'font_size';

    /**
     * @var \Dopamedia\StateMachine\Model\StateMachineFacade
     */
    protected $facade;

    /**
     * @inheritDoc
     */
    public function __construct(
        \Dopamedia\StateMachine\Model\StateMachineFacade $facade
    )
    {
        $this->facade = $facade;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('statemachine:draw')
            ->setDescription('draw the state machine')
            ->addArgument(
                self::INPUT_KEY_PROCESS_NAME,
                InputArgument::REQUIRED
            )->addArgument(
                self::INPUT_KEY_FILE_NAME,
                InputArgument::REQUIRED
            )->addOption(
                self::INPUT_KEY_HIGHLIGHT_STATE,
                null,
                InputOption::VALUE_OPTIONAL
            )->addOption(
                self::INPUT_KEY_FORMAT,
                null,
                InputOption::VALUE_OPTIONAL
            )->addOption(
                self::INPUT_KEY_FONT_SIZE,
                null,
                InputOption::VALUE_OPTIONAL
            );
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $processName = $input->getArgument(self::INPUT_KEY_PROCESS_NAME);
        $fileName = $input->getArgument(self::INPUT_KEY_FILE_NAME);

        $highlightState = $input->getOption(self::INPUT_KEY_HIGHLIGHT_STATE);
        $format = $input->getOption(self::INPUT_KEY_FORMAT);
        $fontSize = $input->getOption(self::INPUT_KEY_FONT_SIZE);

        $response = $this->facade->drawProcess($processName, $highlightState, $format, $fontSize);
        file_put_contents($fileName, $response);
    }
}