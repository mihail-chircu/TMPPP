import copy


class ToyPrototype:
    def __init__(self, name: str, price: float, category: str, attributes: dict):
        self.name = name
        self.price = price
        self.category = category
        self.attributes = attributes

    def clone_shallow(self):
        return copy.copy(self)

    def clone_deep(self):
        return copy.deepcopy(self)

    def __str__(self):
        return f"{self.name} ({self.category}) - {self.price:.2f} LEI | {self.attributes}"


class ToyRegistry:
    def __init__(self):
        self._prototypes = {}

    def register(self, key: str, prototype: ToyPrototype):
        self._prototypes[key] = prototype

    def unregister(self, key: str):
        del self._prototypes[key]

    def clone(self, key: str, deep: bool = True) -> ToyPrototype:
        prototype = self._prototypes.get(key)
        if prototype is None:
            raise KeyError(f"Prototip '{key}' nu exista.")
        if deep:
            return prototype.clone_deep()
        return prototype.clone_shallow()

    def list_prototypes(self):
        return list(self._prototypes.keys())
