export const cloneTemplate = (elementId) => {
  const tmpl = document.getElementById(elementId);
  if (!tmpl)
    return null;
  return tmpl.content.cloneNode(true);
}
