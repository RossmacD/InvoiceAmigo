<template>
    <div class="card">
                <div class="card-header">Login</div>
                <div class="card-body">
                        <!-- <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Email Address</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="" required autocomplete="email" autofocus>
                                    <span class="invalid-feedback" role="alert" v-if="message">
                                        <strong>{{ message }}</strong>
                                    </span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password">
                                    <span class="invalid-feedback" role="alert" v-if="message">
                                        <strong>{{ message }}</strong>
                                    </span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" >
                                    <label class="form-check-label" for="remember">
                                        Remember Me
                                    </label>
                                </div>
                            </div>
                        </div> -->

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary" @click="login()">
                                   Login
                                </button>
                                <span class="invalid-feedback" role="alert" v-if="message">
                                    <strong>{{ message }}</strong>
                                </span>
                                    <!--                                
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                       Forgot your password
                                    </a> -->
                            </div>
                        </div>
                        
                            <!-- <div class="col-md-8 col-md-offset-4">
                              <a href="{{url('/redirect')}}" class="btn btn-primary">Login with Google</a>
                            </div> -->
                    </div>
                </div>
            
</template>

<script>
    import axios from 'axios';
    export default {
        name:"Login",
         data() {
            return {
                name: "",
                email:"",
                message:null
            }
        },
        methods:{
            login(){
                let app=this;
                axios
                .post('/api/login/',{
                    email:"ross@rossmail.com",
                    password:"testtest"
                })
                .then(
                    function(response){ 
                        console.log(response);
                        app.name = response.data.name;
                        app.email = response.data.email;
                        localStorage.setItem('token', response.data.token);
                })
                .catch(
                    function(error){
                        console.log(error);
                    }
                )

            }
        },
        mounted () {
            
        }
    }
</script>
