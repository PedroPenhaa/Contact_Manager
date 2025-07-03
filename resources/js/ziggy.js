const Ziggy = {"url":"http:\/\/localhost:8167","port":8167,"defaults":{},"routes":{"contacts.index":{"uri":"contacts","methods":["GET","HEAD"]},"contacts.store":{"uri":"contacts","methods":["POST"]},"contacts.update":{"uri":"contacts\/{contact}","methods":["PUT"],"parameters":["contact"],"bindings":{"contact":"id"}},"contacts.destroy":{"uri":"contacts\/{contact}","methods":["DELETE"],"parameters":["contact"],"bindings":{"contact":"id"}},"contacts.import":{"uri":"contacts\/import","methods":["POST"]},"storage.local":{"uri":"storage\/{path}","methods":["GET","HEAD"],"wheres":{"path":".*"},"parameters":["path"]}}};
if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
  Object.assign(Ziggy.routes, window.Ziggy.routes);
}
export { Ziggy };
