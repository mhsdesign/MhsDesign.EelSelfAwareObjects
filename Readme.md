# MhsDesign.EelSelfAwareObjects

> !!! EXPERIMENTAL Package to tackle the problem of not having a good way to extract variables in EEL.


this will allow you to use objects a bit like you're used to from javascript-world.

Since EEL can only be written in single expressions (which is fine - as we don't need a full-blown language), it can sometimes get to compressed, and one ends up with one line were many EEL helpers are used. I think it would be fun to have a way to variables and even extract closures.

May I present: When you mix Python + Javascript + EEL, you get:

```ts
root = ${proxy({
    propertY: 'I cannot reference "self"',
    propertX: q(node).property('title'),

    process: (self, var) => String.replace('foo', 'bar', String.toLowerCase(var)),
    something: (self) => self.process("Foo Hello World") + String.length(self.propertY),

    init: (self) => self.something() + self.propertX
})}
```

When `init` is specified, it will be automatically called. So it can't take user defined arguments.
If `init` is not defined. The proxy `self` will be returned by `proxy()`

so you can do the following:

```ts
root = Neos.Fusion:Component {
    helper = ${proxy({
        greet: (self, name) => "hello " + name
    })}

    renderer = afx`
        {props.helper.greet('Me')}
    `
}
```
