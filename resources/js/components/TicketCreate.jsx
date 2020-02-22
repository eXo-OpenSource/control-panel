import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import {Button, Modal, Spinner, Form, InputGroup} from 'react-bootstrap';
import axios from "axios";
import TicketListEntry from "./TicketListEntry";

export default class TicketCreate extends Component {
    constructor() {
        super();
        this.state = {
            categories: null,
            title: '',
            category: '',
            message: '',
        };
    }

    async componentDidMount() {
        if(this.state.categories === null) {
            this.loadCategories();
        }
    }

    async send() {
        try {
            const response = await axios.post('/api/tickets', {
                title: this.state.title,
                category: this.state.category,
                message: this.state.message,
            });

            this.props.back(true);
        } catch(error) {
            console.log(error);
        }
    }

    async loadCategories() {
        try {
            const response = await axios.get('/api/tickets/categories');

            this.setState({
                categories: response.data
            });
        } catch (error) {
            console.log(error);
        }
    }

    async onChange(e) {
        this.setState({ [e.target.name]: e.target.value });
    }

    render() {
        if(this.state.categories === null) {
            return <div className="text-center"><Spinner animation="border"/></div>;
        }

        return (
            <>
                <div className="card">
                    <div className="card-header">
                        Ticket erstellen
                    </div>
                    <div className="card-body">
                        <Form>
                            <Form.Group>
                                <Form.Label>Betreff</Form.Label>
                                <InputGroup>
                                    <Form.Control name="title" type="text" placeholder="Betreff" onChange={this.onChange.bind(this)} />
                                </InputGroup>
                            </Form.Group>

                            <Form.Group>
                                <Form.Label>Kategorie</Form.Label>
                                <InputGroup>
                                    <Form.Control as="select" name="category" onChange={this.onChange.bind(this)}>
                                        <option>(Bitte auswählen)</option>
                                        {this.state.categories.map((category, i) => {
                                            return <option key={category.Id} value={category.Id}>{category.Title}</option>;
                                        })}
                                    </Form.Control>
                                </InputGroup>
                            </Form.Group>

                            <Form.Group>
                                <Form.Label>Nachricht</Form.Label>
                                <InputGroup>
                                    <Form.Control as="textarea" rows="3" name="message" placeholder="Nachricht" onChange={this.onChange.bind(this)} />
                                </InputGroup>
                            </Form.Group>

                            <Button onClick={this.send.bind(this)} className="float-right">Erstellen</Button>
                        </Form>
                    </div>
                </div>
            </>
        );
    }
}
