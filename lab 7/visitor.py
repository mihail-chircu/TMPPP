from abc import ABC, abstractmethod


class ToyElement(ABC):
    def __init__(self, name: str, price: float):
        self.name = name
        self.price = price

    @abstractmethod
    def accept(self, visitor: "ToyVisitor"):
        pass


class BoardGameElement(ToyElement):
    def __init__(self, name: str, price: float, players: int):
        super().__init__(name, price)
        self.players = players

    def accept(self, visitor):
        return visitor.visit_board_game(self)


class ElectronicElement(ToyElement):
    def __init__(self, name: str, price: float, battery: str):
        super().__init__(name, price)
        self.battery = battery

    def accept(self, visitor):
        return visitor.visit_electronic(self)


class PlushElement(ToyElement):
    def __init__(self, name: str, price: float, size: str):
        super().__init__(name, price)
        self.size = size

    def accept(self, visitor):
        return visitor.visit_plush(self)


class ToyVisitor(ABC):
    @abstractmethod
    def visit_board_game(self, toy: BoardGameElement) -> str:
        pass

    @abstractmethod
    def visit_electronic(self, toy: ElectronicElement) -> str:
        pass

    @abstractmethod
    def visit_plush(self, toy: PlushElement) -> str:
        pass


class CSVExportVisitor(ToyVisitor):
    def visit_board_game(self, toy):
        return f"{toy.name},{toy.price},board_game,{toy.players} jucatori"

    def visit_electronic(self, toy):
        return f"{toy.name},{toy.price},electronic,baterii {toy.battery}"

    def visit_plush(self, toy):
        return f"{toy.name},{toy.price},plus,marime {toy.size}"


class XMLExportVisitor(ToyVisitor):
    def visit_board_game(self, toy):
        return (f'<toy type="board_game"><name>{toy.name}</name>'
                f'<price>{toy.price}</price><players>{toy.players}</players></toy>')

    def visit_electronic(self, toy):
        return (f'<toy type="electronic"><name>{toy.name}</name>'
                f'<price>{toy.price}</price><battery>{toy.battery}</battery></toy>')

    def visit_plush(self, toy):
        return (f'<toy type="plush"><name>{toy.name}</name>'
                f'<price>{toy.price}</price><size>{toy.size}</size></toy>')


class TaxCalculatorVisitor(ToyVisitor):
    TAX_RATES = {"board_game": 0.09, "electronic": 0.19, "plush": 0.05}

    def visit_board_game(self, toy):
        tax = toy.price * self.TAX_RATES["board_game"]
        return f"{toy.name}: {toy.price:.2f} + TVA {tax:.2f} = {toy.price + tax:.2f} LEI"

    def visit_electronic(self, toy):
        tax = toy.price * self.TAX_RATES["electronic"]
        return f"{toy.name}: {toy.price:.2f} + TVA {tax:.2f} = {toy.price + tax:.2f} LEI"

    def visit_plush(self, toy):
        tax = toy.price * self.TAX_RATES["plush"]
        return f"{toy.name}: {toy.price:.2f} + TVA {tax:.2f} = {toy.price + tax:.2f} LEI"
