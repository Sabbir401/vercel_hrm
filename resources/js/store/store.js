// import axios from "axios";
import { createStore } from "vuex";

const store = createStore({
    state:{
        //variables
        token: localStorage.getItem('token') || 0,
        employeeId: null,
        tokenTimeout: null,
    },

    mutations:{
        //update variable value
        UPDATE_TOKEN:function(state, payload){
            state.token = payload
        },
        SET_EMPLOYEE_ID: function (state, payload) {
            state.employeeId = payload;
        },
        SET_TOKEN_TIMEOUT: function (state, payload) {
            state.tokenTimeout = payload;
        },
    },

    actions:{
        // action to be performed
        setToken: function(context, payload){
            localStorage.setItem('token',payload);
            context.commit('UPDATE_TOKEN', payload);

            if (context.state.tokenTimeout) {
                clearTimeout(context.state.tokenTimeout);
            }

            const timeoutId = setTimeout(() => {
                context.dispatch('removeToken');
            }, 7200000);

            context.commit('SET_TOKEN_TIMEOUT', timeoutId);
        },
        removeToken: function(context){
            localStorage.removeItem('token')
            context.commit('UPDATE_TOKEN',0)
        },
        setEmployeeId: function (context, payload) {
            context.commit('SET_EMPLOYEE_ID', payload);
          },

    },

    getters:{
        // get state variable value
        getToken: function(state){
            return state.token
        },
        getEmployeeId: function (state) {
            return state.employeeId;
          },
    }
})

export default store;