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
        $idParam = $request->query->get('id', null);
        $level = $request->query->get('level', null);
        $teacher = $request->query->get('teacher', null);
        $style = $request->query->get('style', null);

        if ($idParam !== null && $idParam !== '') {

            $id = (int) $idParam;
            $session = $repo->find($id);

            if (!$session) {
            return $this->json(['error' => 'Session not found'], 404);
        }

        if ($level !== null && $level !== '' && $session->getClassType()?->getLevel() !== $level) {
            return $this->json([]); // aucun résultat
        }

        if ($teacher !== null && $teacher !== '' && strcasecmp($session->getTeacher()?->getFirstName() ?? '', $teacher) !== 0) {
                return $this->json([]); // pas de match
        }

        if ($style !== null && $style !== '' && strcasecmp($session->getClassType()?->getStyle() ?? '', $style) !== 0) {
                return $this->json([]); // pas de match
        }

            $sessions = [$session];
        }
        
        else {

            $sessions = $repo->findByFilters($level, $teacher, $style);

        }

        // On transforme les entités en tableau simple (évite les erreurs de sérialisation)
        $data = array_map(fn($s) => [
            'id'      => $s->getId(),
            'title'    => $s->getClassType()?->getTitle(),
            'level'    => $s->getClassType()?->getLevel(),
            'teacher'    => $s->getTeacher()?->getfirstName(),
            'capacity' => $s->getCapacity(),
            'startAt' => $s->getStartAt()?->format(DATE_ATOM),
            'endAt' => $s->getEndAt()?->format(DATE_ATOM),
        ], $sessions);

        return $this->json($data);
    }
}
