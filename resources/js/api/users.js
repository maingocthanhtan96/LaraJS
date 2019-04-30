import request from '@/utils/request';

export function list(data){
	return request({
		url: '/users/list',
		method: 'get',
		params: data
	});
}

export function roles() {
	return request({
		url: 'users/roles',
		method: 'get'
	});
}

export function store(data) {
	return request({
		url: '/users/storeOrUpdate',
		method: 'post',
		data: data
	});
};

export function update(data, id) {
	return request({
		url: `/users/storeOrUpdate/${id}`,
		method: 'put',
		data: data
	});
};

export function edit(id) {
	return request({
		url:`/users/edit/${id}`,
		method: 'get'
	})
}

export function remove(id) {
	return request({
		url: `/users/delete/${id}`,
		method: 'delete'
	});
}