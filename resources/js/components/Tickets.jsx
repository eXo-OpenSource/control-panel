import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import { Button, Modal, Spinner, Form } from 'react-bootstrap';
import axios from "axios";

export default class Tickets extends Component {
    constructor() {
        super();
        this.state = {
            show: false,
            data: null
        };

        this.handleClose = () => {
            this.setState({ show: false });
        };

        this.handleShow = () => {
            this.setState({ show: true });
        };
    }

    async componentDidMount() {
        if(this.state.data === null) {
            this.loadData();
        }
    }

    async loadData() {
        const response = await axios.get('/api/tickets');

        try {
            this.setState({
                data: response.data
            });
        } catch (error) {
            console.log(error);
        }
    }

    render() {
        if(this.state.data === null) {
            return <div className="text-center"><Spinner animation="border"/></div>;
        }

        return (
            <>
                <table className="table table-sm">
                    <tr>
                        <th>
                            User
                        </th>
                        <th>
                            123
                        </th>
                    </tr>
                    <tr>
                        <td>
                            King
                        </td>
                        <td>
                            Test
                        </td>
                    </tr>
                </table>
            </>
        );
    }
}

var tickets = document.getElementsByTagName('react-tickets');

for (var index in tickets) {
    const component = tickets[index];
    if(typeof component === 'object') {
        const props = Object.assign({}, component.dataset);
        ReactDOM.render(<Tickets {...props} />, component);
    }
}

