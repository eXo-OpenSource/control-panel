import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import {Button, Modal, Spinner, Form, InputGroup} from 'react-bootstrap';
import axios from "axios";
import TicketListEntry from "./TicketListEntry";
import {Link, withRouter} from "react-router-dom";
import {toast, ToastContainer} from "react-toastify";
import Select from 'react-select';
import AsyncSelect from 'react-select/async';
import {forEach} from "react-bootstrap/cjs/ElementChildren";

const options = [
    { value: 'chocolate', label: 'Chocolate' },
    { value: 'strawberry', label: 'Strawberry' },
    { value: 'vanilla', label: 'Vanilla' },
];

class TicketCreate extends Component {
    constructor() {
        super();
        this.state = {
            categories: null,
            categoryOptions: null,
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
            let options = [];

            response.data.forEach((category) => {
               options.push({value: category.Id, label: category.Title});
            });

            this.setState({
                categories: response.data,
                categoryOptions: options
            });
        } catch (error) {
            console.log(error);
        }
    }

    async onChange(e) {
        this.setState({ [e.target.name]: e.target.value });
    }

    async onChangeCategory(value) {
        this.setState({ category: value.value, fields: {} });
    }

    async onChangeField(e) {
        let fields = this.state.fields;
        fields[e.target.name] = e.target.value;

        this.setState({ fields: fields });
    }

    async onChangeSelect(field, newValue) {
        let fields = this.state.fields;

        fields[field] = null;

        if (Array.isArray(newValue)) {
            var entries = [];

            for(var index in newValue)
            {
                entries.push(newValue[index].value);
            }

            fields[field] = entries;
        } else {
            if (newValue && newValue.value) {
                fields[field] = newValue.value;
            }
        }


        this.setState({ fields: fields });
    }

    async loadUsers(inputValue, callback) {
        try {
            const response = await axios.post('/api/users/search', {
                name: inputValue
            });

            let users = [];

            response.data.forEach((entry) => {
               users.push({
                   value: entry.Id,
                   label: entry.Name
               });
            });

            callback(users);
        } catch (error) {
            console.log(error);
        }
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
                        if (field.Type === 'textarea') {
                            return (
                                <Form.Group key={field.Id}>
                                    <Form.Label>{field.Name}</Form.Label>
                                    <Form.Control name={'field' + field.Id} as="textarea" rows="3" placeholder={field.Name} onChange={this.onChangeField.bind(this)}  />
                                    {field.Description ? <Form.Text className="text-muted" dangerouslySetInnerHTML={{__html: field.Description}}>
                                    </Form.Text> : ''}
                                </Form.Group>
                            );
                        }

                        if (field.Type === 'checkbox') {
                            return (
                                <Form.Group key={field.Id}>
                                    <Form.Check type="checkbox" name={'field' + field.Id} label={field.Name} onChange={this.onChangeField.bind(this)} />
                                    {field.Description ? <Form.Text className="text-muted" dangerouslySetInnerHTML={{__html: field.Description}}>
                                    </Form.Text> : ''}
                                </Form.Group>
                            );
                        }

                        if (field.Type === 'user') {
                            return (
                                <Form.Group key={field.Id}>
                                    <Form.Label>{field.Name}</Form.Label>
                                    <AsyncSelect
                                        className="react-select-container"
                                        classNamePrefix="react-select"
                                        placeholder={field.Name}
                                        name={'field' + field.Id}
                                        loadOptions={this.loadUsers.bind(this)}
                                        styles={{option: (provided, state) => { return {...provided, backgroundColor: 'transparent'}}}}
                                        onChange={this.onChangeSelect.bind(this, 'field' + field.Id)}
                                    />
                                    {field.Description ? <Form.Text className="text-muted" dangerouslySetInnerHTML={{__html: field.Description}}>
                                    </Form.Text> : ''}
                                </Form.Group>
                            );
                        }

                        if (field.Type === 'users') {
                            return (
                                <Form.Group key={field.Id}>
                                    <Form.Label>{field.Name}</Form.Label>
                                    <AsyncSelect
                                        closeMenuOnSelect={false}
                                        name={'field' + field.Id}
                                        placeholder={field.Name}
                                        isMulti
                                        cacheOptions
                                        defaultOptions
                                        className="react-select-container"
                                        classNamePrefix="react-select"
                                        loadOptions={this.loadUsers.bind(this)}
                                        styles={{option: (provided, state) => { return {...provided, backgroundColor: 'transparent'}}}}
                                        onChange={this.onChangeSelect.bind(this, 'field' + field.Id)}
                                    />
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
                                        <Select
                                            placeholder="Auswählen"
                                            className="react-select-container"
                                            classNamePrefix="react-select"
                                            options={this.state.categoryOptions}
                                            styles={{option: (provided, state) => { return {...provided, backgroundColor: 'transparent'}}}}
                                            onChange={this.onChangeCategory.bind(this)}
                                        />
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
