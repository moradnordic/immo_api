<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PropertyFormSessionManager
{
    private const SESSION_KEY = 'property_form_data';

    private SessionInterface $session;

    public function __construct(RequestStack $requestStack)
    {
        $this->session = $requestStack->getSession();
    }

    public function saveStep1Data(array $data): void
    {
        $sessionData = $this->getSessionData();
        $sessionData['step1'] = $data;
        $this->session->set(self::SESSION_KEY, $sessionData);
    }

    public function saveStep2Data(array $data): void
    {
        $sessionData = $this->getSessionData();
        $sessionData['step2'] = $data;
        $this->session->set(self::SESSION_KEY, $sessionData);
    }

    public function getStep1Data(): array
    {
        $sessionData = $this->getSessionData();
        return $sessionData['step1'] ?? [];
    }

    public function getStep2Data(): array
    {
        $sessionData = $this->getSessionData();
        return $sessionData['step2'] ?? [];
    }

    public function getAllData(): array
    {
        return $this->getSessionData();
    }

    public function clearData(): void
    {
        $this->session->remove(self::SESSION_KEY);
    }

    public function hasStep1Data(): bool
    {
        $sessionData = $this->getSessionData();
        return !empty($sessionData['step1']);
    }

    public function hasStep2Data(): bool
    {
        $sessionData = $this->getSessionData();
        return !empty($sessionData['step2']);
    }

    public function getPropertyType(): ?string
    {
        $step1Data = $this->getStep1Data();
        return $step1Data['type'] ?? null;
    }

    private function getSessionData(): array
    {
        return $this->session->get(self::SESSION_KEY, []);
    }
}