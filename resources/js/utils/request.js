import {
	SET_ERRORS,
	CLEAR_ERRORS
} from '../store/muation-types';

import { Message } from 'element-ui';

import axios from 'axios';

import store from '../store/index';

import {
	getToken, setToken
} from "./auth";

// Create axios instance
const service = axios.create({
	baseURL: process.env.MIX_BASE_API,
	timeout: 10000 // Request timeout
});

// request
service.interceptors.request.use(
	config => {
		let token = getToken() || false;
		if (token) {
			config.headers['Authorization'] = 'Bearer ' + token; // Set JWT token
		}
		store.dispatch(CLEAR_ERRORS);
		return config;
	},
	error => {
		// Do something with request error
		console.log('Error request: ', error); // for debug
		return Promise.reject(error);
	}
);

// response pre-processing
service.interceptors.response.use(
	response => {
		if (response.headers.authorization) {
			console.log(response.headers.authorization);
			setToken(response.headers.authorization);
			response.data.token = response.headers.authorization
		}
		store.dispatch(CLEAR_ERRORS);
		return response;
	},
	error => {
		if(error.response) {
			if(error.response.data.errors){
				store.dispatch(SET_ERRORS, error.response.data.errors);
			}else{
				Message({
					message: error.response.data,
					type: 'error',
					duration: 5 * 1000
				});
			}
			console.log('Error response: ' + error); // for debug

			return Promise.reject(error);
		}
	}
);

export default service;
