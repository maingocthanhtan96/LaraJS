import request from '@/utils/request';

export function login(form) {
	return request({
		url:'/login',
		method: 'post',
		data: form
	});
}

export function userInfo(){
	return request({
		url: '/user',
		method: 'get'
	});
}

export function logout(){
	return request({
		url: '/logout',
		method: 'get'
	})
}