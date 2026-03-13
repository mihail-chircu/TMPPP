from abc import ABC, abstractmethod


class SupportRequest:
    def __init__(self, issue: str, severity: int):
        self.issue = issue
        self.severity = severity
        self.response = None

    def __str__(self):
        status = self.response or "Neprocesat"
        return f"[Sev {self.severity}] {self.issue} -> {status}"


class SupportHandler(ABC):
    def __init__(self):
        self._next = None

    def set_next(self, handler: "SupportHandler") -> "SupportHandler":
        self._next = handler
        return handler

    def handle(self, request: SupportRequest) -> SupportRequest:
        if self.can_handle(request):
            request.response = self.process(request)
            return request
        if self._next:
            return self._next.handle(request)
        request.response = "Niciun handler disponibil"
        return request

    @abstractmethod
    def can_handle(self, request: SupportRequest) -> bool:
        pass

    @abstractmethod
    def process(self, request: SupportRequest) -> str:
        pass


class FAQHandler(SupportHandler):
    FAQ = {
        "program": "Magazinul KINDER e deschis 09:00-21:00",
        "retur": "Returul se face in 30 de zile cu bon fiscal",
        "livrare": "Livrare gratuita la comenzi peste 200 LEI",
    }

    def can_handle(self, request: SupportRequest) -> bool:
        return request.severity == 1

    def process(self, request: SupportRequest) -> str:
        for key, answer in self.FAQ.items():
            if key in request.issue.lower():
                return f"[FAQ] {answer}"
        return "[FAQ] Consultati sectiunea Intrebari Frecvente de pe site"


class CustomerServiceHandler(SupportHandler):
    def can_handle(self, request: SupportRequest) -> bool:
        return request.severity == 2

    def process(self, request: SupportRequest) -> str:
        return f"[Serviciu Clienti] Cererea '{request.issue}' a fost preluata de un operator"


class ManagerHandler(SupportHandler):
    def can_handle(self, request: SupportRequest) -> bool:
        return request.severity == 3

    def process(self, request: SupportRequest) -> str:
        return f"[Manager] Cererea '{request.issue}' escaladata la management"


class DirectorHandler(SupportHandler):
    def can_handle(self, request: SupportRequest) -> bool:
        return request.severity >= 4

    def process(self, request: SupportRequest) -> str:
        return f"[Director] Cererea critica '{request.issue}' preluata de directorul magazinului"
