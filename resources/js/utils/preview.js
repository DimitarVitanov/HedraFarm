// preview.js - picks which storefront is rendered: PagesV2 (redesign, live) or Pages (old design).
//   ?preview=v1  -> switch this browser tab back to the old design (sticks while browsing)
//   ?preview=v2  -> switch back to the redesign
// To roll back for everyone, change DEFAULT_VERSION to 'v1'.
export const DEFAULT_VERSION = 'v2'

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
