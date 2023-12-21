<?php

namespace App\Command;

use App\Entity\City;
use App\Service\WeatherService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[AsCommand(
    name: 'updateWeather',
    description: 'Get temperature for cities saved in database.'
)]
class UpdateWeatherCommand extends Command
{

    public function __construct(
        private readonly WeatherService $weatherService,
        private readonly EntityManagerInterface $entityManager,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('This command allows you to calculate how many knights fit on the chessboard.')
            ->addArgument('cities', InputArgument::OPTIONAL|InputArgument::IS_ARRAY, 'Update temperature in cities (all cities if not specified).');
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ExceptionInterface
     * */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $cities = $input->getArgument('cities');

        if ($cities) {
            foreach ($cities as $cityName) {
                $city = $this->entityManager->getRepository(City::class)->findOneBy(['name' => $cityName]);
                if ($city) {
                    $city->setWeather($this->weatherService->updateWeatherForCityByName($cityName));

                    $this->entityManager->persist($city);
                }
            }
        } else {
            $cities = $this->entityManager->getRepository(City::class)->findAll();
            foreach ($cities as $city) {
                $city->setWeather(
                    $this->weatherService->updateWeatherForCityByName($city->getName())
                );

                $this->entityManager->persist($city);
            }
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
