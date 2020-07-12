import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import TicketListEntry from "../TicketListEntry";
import { Beforeunload } from 'react-beforeunload';

export default class AdminPolls extends Component {
    constructor() {
        super();
        this.state = {
            users: []
        };


        Echo.private(`admin.polls`)
            .listen('PollUpdate', this.pollUpdate.bind(this));

        Echo.join(`admin.polls`)
            .here((users) => {
                this.setState({users: users});
            })
            .joining((user) => {
                console.log('Joining', user);
                const users = this.state.users;

                let i = users.length
                while (i--) {
                    if (users[i].id === user.id) {
                        users.splice(i, 1);
                    }
                }

                users.push(user);
                this.setState({users: users});
            })
            .leaving((user) => {
                console.log('Leaving', user);
                const users = this.state.users;

                let i = users.length
                while (i--) {
                    if (users[i].id === user.id) {
                        users.splice(i, 1);
                    }
                }

                this.setState({users: users});
            });

    }

    async componentDidMount() {
    }

    async pollUpdate(data) {
        console.log(data);
        /*
        let newData = this.state.data;

        let index = -1;

        newData.forEach((element, i) => {
            if(element.Id === data.ticket.Id) {
                index = i;
            }
        })


        if(index !== -1) {
            this.state.data[index] = data.ticket;
            this.setState({
                data: newData
            });
        } else {
            this.state.data.push(data.ticket);
            this.setState({
                data: newData
            });
        }
        */
    }

    render() {
        return (
            <Beforeunload onBeforeunload={() => Echo.leave(`admin.polls`)}>
                <h1>Hello</h1>
                {this.state.users.map((user, i) => {
                    return <span key={user.id}>{user.name}</span>
                })}
            </Beforeunload>
        );
    }
}

var polls = document.getElementsByTagName('react-admin-polls');

for (var index in polls) {
    const component = polls[index];
    if(typeof component === 'object') {
        const props = Object.assign({}, component.dataset);
        ReactDOM.render(<AdminPolls {...props} />, component);
    }
}

