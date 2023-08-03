const MutationMap = new Map();

const observer = new MutationObserver(mutations => {
  mutations.forEach(m => {
    m.addedNodes.forEach(n => {
      if (n.nodeType === 1) {
        MutationMap.forEach((ConstructorName, selector) => {
          if (n.matches(selector)) {
            new ConstructorName(n);
          }
        });
      }
    })
  })
});

observer.observe(document.documentElement, {
  childList: true,
  subtree: true
});

const connect = (selector, ConstructorName) => {
  MutationMap.set(selector, ConstructorName);
  document.querySelectorAll(selector).forEach(element => {
    new ConstructorName(element);
  })
}

export {connect};
