<?php

namespace App\Tests\Controller;

use App\Entity\Agency;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class AgencyControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/agency/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Agency::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Agency index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'agency[name]' => 'Testing',
            'agency[code]' => 'Testing',
            'agency[tel]' => 'Testing',
            'agency[responsible]' => 'Testing',
            'agency[region]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Agency();
        $fixture->setName('My Title');
        $fixture->setCode('My Title');
        $fixture->setTel('My Title');
        $fixture->setResponsible('My Title');
        $fixture->setRegion('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Agency');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Agency();
        $fixture->setName('Value');
        $fixture->setCode('Value');
        $fixture->setTel('Value');
        $fixture->setResponsible('Value');
        $fixture->setRegion('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'agency[name]' => 'Something New',
            'agency[code]' => 'Something New',
            'agency[tel]' => 'Something New',
            'agency[responsible]' => 'Something New',
            'agency[region]' => 'Something New',
        ]);

        self::assertResponseRedirects('/agency/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getCode());
        self::assertSame('Something New', $fixture[0]->getTel());
        self::assertSame('Something New', $fixture[0]->getResponsible());
        self::assertSame('Something New', $fixture[0]->getRegion());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Agency();
        $fixture->setName('Value');
        $fixture->setCode('Value');
        $fixture->setTel('Value');
        $fixture->setResponsible('Value');
        $fixture->setRegion('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/agency/');
        self::assertSame(0, $this->repository->count([]));
    }
}
