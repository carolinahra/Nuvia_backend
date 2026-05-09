<?php

namespace App\Service;

use App\Entity\User;

class TmbService
{
    private const ACTIVITY_FACTORS = [
        'sedentary'  => 1.2,    // Poco o ningún ejercicio
        'light'      => 1.375,  // Ejercicio ligero (1-3 días/semana)
        'moderate'   => 1.55,   // Ejercicio moderado (3-5 días/semana)
        'active'     => 1.725,  // Ejercicio fuerte (6-7 días/semana)
        'very_active' => 1.9,   // Ejercicio muy fuerte (2 veces/día)
    ];

    /**
     * Calcula la TMB, GET e IMC del usuario.
     *
     * @param User   $user
     * @param string $activityLevel  Clave de ACTIVITY_FACTORS
     * @return array{tmb: float, get: float, imc: float|null, ingestaDiariaKcal: int}
     */
    public function calculate(User $user, string $activityLevel): array
    {
        $weightKg = $this->getLatestWeight($user);
        $heightCm = $user->getHeightCm();
        $birthdate = $user->getBirthdate();
        $sex = $user->getSex();

        if ($weightKg === null || $heightCm === null || $birthdate === null || $sex === null) {
            throw new \InvalidArgumentException(
                'Datos incompletos: se necesitan peso, altura, fecha de nacimiento y sexo para calcular la TMB.'
            );
        }

        $age = (new \DateTime())->diff($birthdate)->y;

        if ($age <= 0 || $weightKg <= 0 || $heightCm <= 0) {
            throw new \InvalidArgumentException('Los valores de peso, altura y edad deben ser positivos.');
        }

        // Fórmula Mufflin-St Jeor
        if ($sex === 'male') {
            $tmb = (10 * $weightKg) + (6.25 * $heightCm) - (5 * $age) + 5;
        } else {
            $tmb = (10 * $weightKg) + (6.25 * $heightCm) - (5 * $age) - 161;
        }

        // GET = TMB × factor de actividad
        $factor = self::ACTIVITY_FACTORS[$activityLevel] ?? self::ACTIVITY_FACTORS['sedentary'];
        $get = $tmb * $factor;

        // IMC = peso / (altura en metros)²
        $heightM = $heightCm / 100;
        $imc = $weightKg / ($heightM * $heightM);

        return [
            'tmb'               => round($tmb, 1),
            'get'               => round($get, 1),
            'imc'               => round($imc, 1),
            'ingestaDiariaKcal' => (int) round($get),
        ];
    }

    /**
     * Obtiene el último peso registrado del usuario.
     */
    private function getLatestWeight(User $user): ?float
    {
        $logs = $user->getWeightLogs()->toArray();

        if (empty($logs)) {
            return null;
        }

        usort($logs, fn ($a, $b) => $b->getCreatedAt() <=> $a->getCreatedAt());

        return $logs[0]->getWeightKg();
    }
}
