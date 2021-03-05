<template>
    <v-container
        class="fill-height"
        fluid
    >
        <v-row
            align="center"
            justify="center"
        >
            <v-col
                cols="12"
                md="8"
                sm="10"
            >
                <v-card class="elevation-12">

                    <v-toolbar
                        flat
                    >
                        <v-toolbar-title>Login form</v-toolbar-title>
<!--                        <v-spacer></v-spacer>-->
                    </v-toolbar>
                    <v-card-text>

                        <v-row class=".flex justify-center mb-6 align-center">
                            <v-col class="d-none d-md-flex">
                                <v-card-text>
                                    <v-img
                                        :src="require('./../../assets/auth-logo.svg')"
                                        aspect-ratio="2"
                                        class="auth-logo"
                                        contain
                                    ></v-img>
                                </v-card-text>
                            </v-col>

                            <v-divider class="d-none d-md-flex" vertical></v-divider>

                            <v-col>
                                <v-card-text>

                                    <div class="display-1 font-weight-light">Welcome back</div>
                                    <div class="font-weight-light">to {{instanceName}} instance.</div>

                                    <v-form>
                                        <v-text-field
                                            color="accent"
                                            label="Login"
                                            name="login"
                                            prepend-icon="mdi-account"
                                            type="text"
                                            v-model="login.username"
                                        ></v-text-field>

                                        <v-text-field
                                            color="accent"
                                            id="password"
                                            label="Password"
                                            name="password"
                                            prepend-icon="mdi-lock"
                                            type="password"
                                            v-model="login.password"
                                        ></v-text-field>

                                        <v-checkbox
                                            label="Remember me"
                                            v-model="login.remember"
                                        ></v-checkbox>

                                    </v-form>
                                </v-card-text>
                            </v-col>
                        </v-row>

                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn @click="register.dialog = !register.dialog" color="primary">Register</v-btn>
                        <v-btn color="primary">Login</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>

        <v-dialog
            v-model="register.dialog"
            width="600px"
        >
            <v-card>
                <v-card-title>
                    <span class="headline">Register</span>
                </v-card-title>
                <v-card-text>

                    <v-stepper non-linear v-model="register.window">
                        <v-stepper-header>
                            <v-stepper-step
                                step="1"
                            >
                                Basic info
                            </v-stepper-step>

                            <v-divider></v-divider>

                            <v-stepper-step
                                step="2"
                            >
                                Validation
                            </v-stepper-step>

                            <v-divider></v-divider>

                            <v-stepper-step
                                step="3"
                            >
                                Chats
                            </v-stepper-step>
                        </v-stepper-header>
                    </v-stepper>

                    <v-window v-model="register.window">
                        <v-window-item :value="1">
                            <v-card-text>
                                <v-text-field
                                    label="Email"
                                ></v-text-field>
                                <span class="caption grey--text text--darken-1">
                                    The email you will use to login. It will be also used for receiving notifications.
                                </span>

                                <v-text-field
                                    label="Password"
                                    type="password"
                                ></v-text-field>
                                <v-text-field
                                    label="Confirm Password"
                                    type="password"
                                ></v-text-field>
                                <span class="caption grey--text text--darken-1">
                                    Please enter a password for your account
                                </span>

                            </v-card-text>
                        </v-window-item>

                        <v-window-item :value="2">
                            <v-card-text>

                            </v-card-text>
                        </v-window-item>

                        <v-window-item :value="3">
                            <div class="pa-4 text-center">

                            </div>
                        </v-window-item>
                    </v-window>
                </v-card-text>
                <v-card-actions>
                    <v-btn
                        :disabled="!register.dialog"
                        @click="register.dialog = !register.dialog"
                        color="blue darken-1"
                        text
                    >
                        Close
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-btn
                        :disabled="register.window === 1"
                        @click="register.window--"
                        color="blue darken-1"
                        text
                    >
                        Back
                    </v-btn>
                    <v-btn
                        :disabled="register.window === 3"
                        @click="register.window++"
                        color="blue darken-1"
                        text
                    >
                        Next
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

    </v-container>
</template>

<script>
    export default {
        name: "Auth",
        data() {
            return {
                instanceName: window.location.host,

                register: {
                    window: 1,
                    dialog: false
                },
                login: {
                    username: "",
                    password: "",
                    remember: false
                }
            }
        }
    }
</script>

<style scoped>

</style>
