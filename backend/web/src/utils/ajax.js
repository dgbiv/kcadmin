import axios from 'axios'
import { message } from 'antd';

// 响应拦截器
axios.interceptors.response.use(function (response) {
    if (response && response.data) {
        const {status, info} = response.data;
        if (status === undefined || status === true || status === 1) {
            return response.data;
        } else if (info) {
            const errorText = `操作失败：${info}`;
            message.error(errorText);
            console.error(errorText);
            return Promise.reject(errorText)
        } else {
            const errorText = `操作失败`;
            message.error(errorText);
            console.error(errorText);
            return Promise.reject(errorText)
        }
    } else if (response) {
        return response;
    } else {
        return Promise.reject(`服务器无响应`);
    }
}, function (error) {
    let errorText = '';

    if (error && error.response && error.response.status === 500) {
        if (error.response.data) {
            errorText = error.response.data.message || error.response.data;
        } else {
            errorText = error.response.message
        }
    } else if (error.response && error.response.status) {
        errorText = `请求出错：${error.response.statusText}（${error.response.status}）`
    } else if (error.message) {
        errorText = error.message
    } else {
        errorText = error
    }

    errorText && message.error(errorText);
    error && console.error(error);
    return Promise.reject(errorText);
});