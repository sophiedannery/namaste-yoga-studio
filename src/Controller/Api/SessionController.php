<?php
namespace App\Controller\Api;

use App\Repository\SessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')] // préfixe pour toutes les routes de cette classe
class SessionController extends AbstractController
{
    // GET /api/sessions
    #[Route('/sessions', name: 'sessions', methods: ['GET'])]
    public function list(Request $request, SessionRepository $repo): JsonResponse
    {
        $level = $request->query->get('level', null);
        $teacher = $request->query->get('teacher', null);
        $style = $request->query->get('style', null);

        $sessions = $repo->findByFilters($level, $teacher, $style);

        $data = array_map(fn($s) => [
            'id'      => $s->getId(),
            'title'    => $s->getClassType()?->getTitle(),
            'level'    => $s->getClassType()?->getLevel(),
            'teacher'    => $s->getTeacher()?->getfirstName(),
            'capacity' => $s->getCapacity(),
            'price' => $s->getPrice(),
            'startAt' => $s->getStartAt()?->format(DATE_ATOM),
            'endAt' => $s->getEndAt()?->format(DATE_ATOM),
        ], $sessions);

        return $this->json($data);
    }
}
