import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import {Button, Modal, Spinner, Form, InputGroup} from 'react-bootstrap';
import axios from "axios";
import TicketListEntry from "./TicketListEntry";
import {Link, withRouter} from "react-router-dom";
import {toast, ToastContainer} from "react-toastify";

class TicketCreate extends Component {
    constructor() {
        super();
        this.state = {
            categories: null,
            title: '',
            category: '',
            message: '',
            submitting: false,
            fields: {},
        };
    }

    async componentDidMount() {
        if(this.state.categories === null) {
            this.loadCategories();
        }
    }

    async send() {
        if(this.state.submitting)
            return false;

        this.setState({
            submitting: true
        });

        axios.post('/api/tickets', {
            title: this.state.title,
            category: this.state.category,
            message: this.state.message,
            fields: this.state.fields,
            submitting: false,
        }).then(() => {
            this.setState({
                submitting: false
            });
            this.props.history.push('/tickets')
        }).catch((error) => {
            this.setState({
                submitting: false
            });

            if(error.response) {
                toast.error(error.response.data.Message);
            } else {
                toast.error('Unbekannter Fehler');
                console.error(error);
            }
        });
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


    async onChangeCategory(e) {
        this.setState({ category: e.target.value, fields: {} });
    }

    async onChangeField(e) {
        let fields = this.state.fields;
        fields[e.target.name] = e.target.value;

        this.setState({ fields: fields });
    }

    render() {
        if(this.state.categories === null) {
            return <div className="text-center"><Spinner animation="border"/></div>;
        }

        let categoryFields = '';

        if(this.state.category && this.state.category !== '') {
            for(var index in this.state.categories) {
                const category = this.state.categories[index];

                if(parseInt(category.Id) !== parseInt(this.state.category)) {
                    continue;
                }

                if(category && category.fields) {
                    categoryFields = category.fields.map((field, i) => {
                        if(field.Type === 'textarea') {
                            return (
                                <Form.Group key={field.Id}>
                                    <Form.Label>{field.Name}</Form.Label>
                                    <Form.Control name={'field' + field.Id} as="textarea" rows="3" placeholder={field.Name} onChange={this.onChangeField.bind(this)}  />
                                    {field.Description ? <Form.Text className="text-muted" dangerouslySetInnerHTML={{__html: field.Description}}>
                                    </Form.Text> : ''}
                                </Form.Group>
                            );
                        }

                        if(field.Type === 'checkbox') {
                            return (
                                <Form.Group key={field.Id}>
                                    <Form.Check type="checkbox" name={'field' + field.Id} label={field.Name} onChange={this.onChangeField.bind(this)} />
                                    {field.Description ? <Form.Text className="text-muted" dangerouslySetInnerHTML={{__html: field.Description}}>
                                    </Form.Text> : ''}
                                </Form.Group>
                            );
                        }

                        return (
                            <Form.Group key={field.Id}>
                                <Form.Label>{field.Name}</Form.Label>
                                <Form.Control name={'field' + field.Id} type={field.Type === 'uuid' ? 'text' : field.Type} placeholder={field.Name} onChange={this.onChangeField.bind(this)} />
                                {field.Description ? <Form.Text className="text-muted" dangerouslySetInnerHTML={{__html: field.Description}}>
                                </Form.Text> : ''}
                            </Form.Group>
                        );
                    })
                }
            }
        }

        return (
            <>
                <ToastContainer />
                <div className="row mb-4">
                    <div className="col-md-12">
                        <Link to="/tickets" className="btn btn-primary float-right">Zurück</Link>
                    </div>
                </div>
                <div className="row">
                    <div className="col-md-12">
                        <div className="card">
                            <div className="card-header">
                                Ticket erstellen
                            </div>
                            <div className="card-body">
                                <Form>
                                    <Form.Group>
                                        <Form.Label>Titel</Form.Label>
                                        <InputGroup>
                                            <Form.Control name="title" type="text" placeholder="Titel" onChange={this.onChange.bind(this)} />
                                        </InputGroup>
                                    </Form.Group>

                                    <Form.Group>
                                        <Form.Label>Kategorie</Form.Label>
                                        <InputGroup>
                                            <Form.Control as="select" name="category" onChange={this.onChangeCategory.bind(this)}>
                                                <option>(Bitte auswählen)</option>
                                                {this.state.categories.map((category, i) => {
                                                    return <option key={category.Id} value={category.Id}>{category.Title}</option>;
                                                })}
                                            </Form.Control>
                                        </InputGroup>
                                    </Form.Group>

                                    {categoryFields}

                                    <Form.Group>
                                        <Form.Label>Nachricht</Form.Label>
                                        <InputGroup>
                                            <Form.Control as="textarea" rows="3" name="message" placeholder="Nachricht" onChange={this.onChange.bind(this)} />
                                        </InputGroup>
                                    </Form.Group>

                                    <Button disabled={this.state.submitting} onClick={this.send.bind(this)} className="float-right">Erstellen</Button>
                                </Form>
                            </div>
                        </div>
                    </div>
                </div>
            </>
        );
    }
}


export default withRouter(TicketCreate);
