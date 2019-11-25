@extends('layouts.master')

@section('content')

<div id="test">
        

    <div v-if="!isValid" >
        <ul>

            <li v-show="!validation.email" >email error</li>
            <li v-show="!validation.password" >password error</li>
        </ul>
    </div>



    <form action="#" @submit.prevent="AddNewUser" method="POST">

        <div>
            <label for="email">email</label>
            <input v-model="newUser.email" type="text" id="email" name="email">
        </div>
        <div>
            <label for="password">password</label>
            <input v-model="newUser.password" type="password" id="password" name="password">
        </div>
        <!-- <button type="submit" :disabled="!isValid" >Add</button> -->
        <button :disabled="!isValid"  type="submit" v-if="!edit">Add New User</button>
        <button :disabled="!isValid"  type="submit" v-if="edit" @click="EditUser(newUser.id)">Edit User</button>





    </form>


        <div v-if="success">Add new user successful.</div>
        <table>
            <tr v-for=" user in users ">
                <td> @{{ user.college.first.id }}</td>
                <td> @{{ user.firstname }}</td>
                <td> @{{ user.lastname }}</td>
                <td>
                    <button  @click="ShowUser(user.id)">Edit</button>
                    <button  @click="RemoveUser(user.id)">Remove</button>
                </td>
            </tr>
        </table>

    </div>

@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('js/test.js') }}"></script>
@endpush