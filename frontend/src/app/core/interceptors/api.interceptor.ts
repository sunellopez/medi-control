import { HttpInterceptorFn } from '@angular/common/http';

export const apiInterceptor: HttpInterceptorFn = (req, next) => {
  const isApiUrl = !req.url.startsWith('http') && !req.url.includes('assets/');
  if (isApiUrl) {
    const apiUrl = 'http://localhost:8000/api';
    // Ensure we don't duplicate slashes
    const path = req.url.startsWith('/') ? req.url.substring(1) : req.url;
    const apiReq = req.clone({ url: `${apiUrl}/${path}` });
    return next(apiReq);
  }
  return next(req);
};
