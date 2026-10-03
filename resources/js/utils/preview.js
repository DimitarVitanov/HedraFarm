// preview.js - lets the redesigned storefront (PagesV2) be viewed without replacing the live one.
//   ?preview=v2  -> switch this browser tab to the new design (sticks while browsing)
//   ?preview=v1  -> switch back to the current design
// To launch the new design for everyone, change DEFAULT_VERSION to 'v2'.
export const DEFAULT_VERSION = 'v1'

const KEY = 'storefrontPreview'

export function storefrontVersion() {
  try {
    const param = new URLSearchParams(window.location.search).get('preview')
    if (param === 'v1' || param === 'v2') {
      sessionStorage.setItem(KEY, param)
    }
    return sessionStorage.getItem(KEY) || DEFAULT_VERSION
  } catch (error) {
    return DEFAULT_VERSION
  }
}

export const isPreviewing = () => storefrontVersion() !== DEFAULT_VERSION
