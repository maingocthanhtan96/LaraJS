import Cookies from 'js-cookie';

const ACCESS_TOKEN = 'access-token';
const REFRESH_TOKEN = 'refresh-token';

export function getAccessToken() {
  return Cookies.get(ACCESS_TOKEN);
}
export function getRefreshToken() {
  return Cookies.get(REFRESH_TOKEN);
}

export function setAccessToken(token, expires = 5) {
  return Cookies.set(ACCESS_TOKEN, token, { expires: expires });
}
export function setRefreshToken(token, expires = 15) {
  return Cookies.set(REFRESH_TOKEN, token, { expires: expires });
}

export function removeAccessToken() {
  return Cookies.remove(ACCESS_TOKEN);
}
export function removeRefreshToken() {
  return Cookies.remove(REFRESH_TOKEN);
}
