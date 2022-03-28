# MhsDesign.EelSelfAwareObjects

!!! EXPERIMENTAL Package to tackle the problem of not having a good way to extract variables in eel.


this will you allow writing crazy things:

```
root = ${call({
    fooVariable: 'FOO',
    nodeVariable: q(node).property('title'),
    
    getValueX: (self, bar) => 'X' + bar + self.fooVariable,
    doSomething: (self) => String.toLowerCase(self.fooVariable + self.nodeVariable + call(self.getValueX, 'bing')),
    
    __call__: (self) => call(self.doSomething)
})}
```

`__call__` is the entry point and called automatically by `call()`

-------

technically we don't need a `call` function but only a `makeObjectSelfAwareAndCallable` function that would be used like:

```
root = ${(makeObjectSelfAwareAndCallable({
    fooVariable: 'FOO',    
    getValueX: (self) => 'X' + self.fooVariable,
    __call__: (self) => (self.getValueX)())
}))()}
```

but we don't have the `($varibale)()` syntax in eel yet.
