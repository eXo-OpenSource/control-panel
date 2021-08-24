import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import {Button, Modal, Spinner, Form, InputGroup, OverlayTrigger, Tooltip, Row, Col} from 'react-bootstrap';
import axios from "axios";
import TicketListEntry from "./TicketListEntry";
import SelectUserDialog from '../helpers/SelectUserDialog';
import {
    Link,
    useParams
} from "react-router-dom";
import ConfirmDialog from '../helpers/ConfirmDialog';
import SelectUserFromListDialog from "../helpers/SelectUserFromListDialog";
import { ToastContainer, toast } from 'react-toastify';
import Linkify from 'react-linkify';
import 'react-toastify/dist/ReactToastify.css';
import TicketEntryChat from "./TicketEntryChat";
import TicketEntryThread from "./TicketEntryThread";

export default class TicketEntry extends Component {
    constructor({match}) {
        super();
        this.state = {
            data: null,
            message: '',
            ticketId: match.params.ticketId,
            showAddUserDialog: false,
            showAssignUserDialog: false,
            showRemoveUserDialog: false,
            showDeleteTicketDialog: false,
            showCloseTicketDialog: false,
            showOpenTicketDialog: false,
            submitting: false,
        };

        Echo.private(`tickets.${this.state.ticketId}`)
            .listen('TicketUpdated', this.updateTicket.bind(this));
    }

    async updateTicket(data) {
        this.setState({
            data: data.ticket
        });
    }
    async componentDidMount() {
        if(this.state.data === null) {
            this.loadData();
        }
    }

    async toggleAddUserDialog() {
        this.setState({showAddUserDialog: !this.state.showAddUserDialog});
    }

    async toggleAssignUserDialog() {
        this.setState({showAssignUserDialog: !this.state.showAssignUserDialog});
    }

    async toggleRemoveUserDialog(userId) {
        this.setState({showRemoveUserDialog: !this.state.showRemoveUserDialog, removeUserId: userId});
    }

    async toggleDeleteTicketDialog() {
        this.setState({showDeleteTicketDialog: !this.state.showDeleteTicketDialog});
    }

    async toggleCloseTicketDialog() {
        this.setState({showCloseTicketDialog: !this.state.showCloseTicketDialog});
    }

    async toggleOpenTicketDialog() {
        this.setState({showOpenTicketDialog: !this.state.showOpenTicketDialog});
    }

    async hideRemoveUserDialog() {
        this.setState({showRemoveUserDialog: false});
    }

    async hideDeleteTicketDialog() {
        this.setState({showDeleteTicketDialog: false});
    }

    async hideCloseTicketDialog() {
        this.setState({showCloseTicketDialog: false});
    }

    async hideOpenTicketDialog() {
        this.setState({showOpenTicketDialog: false});
    }

    async onChange(e) {
        this.setState({ [e.target.name]: e.target.value });
    }

    async send(message) {
        if(this.state.submitting)
            return false;

        await this.setState({
            message: message
        });

        if(this.state.message === '')
            return false;

        this.setState({
            submitting: true
        });

        axios.put('/api/tickets/' + this.state.ticketId, {
            type: 'addMessage',
            message: this.state.message,
        }).then(() => {
            this.setState({
                submitting: false,
                message: ''
            });
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
        return true;
    }

    async close() {
        if(this.state.submitting)
            return false;

        this.setState({
            submitting: true
        });

        axios.put('/api/tickets/' + this.state.ticketId, {
            type: 'close',
        }).then(() => {
            this.setState({
                submitting: false
            });
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

    async open() {
        if(this.state.submitting)
            return false;

        this.setState({
            submitting: true
        });

        axios.put('/api/tickets/' + this.state.ticketId, {
            type: 'open',
        }).then(() => {
            this.setState({
                submitting: false
            });
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

    async deleteTicket() {
        if(this.state.submitting)
            return false;

        this.setState({
            submitting: true
        });

        axios.put('/api/tickets/' + this.state.ticketId, {
            type: 'delete',
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

    async loadData() {
        const response = await axios.get('/api/tickets/' + this.state.ticketId);

        try {
            this.setState({
                data: response.data
            });
        } catch (error) {
            console.log(error);
        }
    }

    async addUser(userId) {
        if(this.state.submitting)
            return false;

        this.setState({
            submitting: true
        });

        axios.put('/api/tickets/' + this.state.ticketId, {
            type: 'addUser',
            newUserId: userId
        }).then(() => {
            this.setState({
                submitting: false
            });
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

    async hideUserDialog() {
        this.setState({showAddUserDialog: false});
    }

    async assignUser(userId) {
        if(this.state.submitting)
            return false;

        this.setState({
            submitting: true
        });

        axios.put('/api/tickets/' + this.state.ticketId, {
            type: 'assignToUser',
            assignUserId: userId
        }).then(() => {
            this.setState({
                submitting: false
            });
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

    async hideAssignUserDialog() {
        this.setState({showAssignUserDialog: false});
    }

    async removeUser() {
        if(this.state.submitting)
            return false;

        this.setState({
            submitting: true
        });

        axios.put('/api/tickets/' + this.state.ticketId, {
            type: 'removeUser',
            removeUserId: this.state.removeUserId
        }).then(() => {
            this.setState({
                submitting: false
            });
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

    async selfAssign() {
        if(this.state.submitting)
            return false;

        this.setState({
            submitting: true
        });

        axios.put('/api/tickets/' + this.state.ticketId, {
            type: 'assignToUser',
            assignUserId: Exo.UserId
        }).then(() => {
            this.setState({
                submitting: false
            });
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

    render() {
        if(this.state.data === null) {
            return <div className="text-center"><Spinner animation="border"/></div>;
        }

        let closeButton = <></>;
        let canAnswer = false;
        let assignButtons = <></>;
        let addUserButton = <></>;

        if(this.state.data.State === 'Open') {
            if(Exo.Rank > 0 || this.state.data.UserId == Exo.UserId) {
                closeButton = <Row>
                                <Col>
                                    <Button disabled={this.state.submitting} onClick={this.toggleCloseTicketDialog.bind(this)} variant="danger">Ticket schließen</Button>
                                    {Exo.Rank >= 7 ? <Button className="ml-1" disabled={this.state.submitting} onClick={this.toggleDeleteTicketDialog.bind(this)} variant="danger">Ticket löschen</Button> : ''}
                                </Col>
                              </Row>;
            }
            if(Exo.Rank >= 1) {
                assignButtons = <Row className="mb-1">
                    <Col>
                        <div className="btn-group" role="group" >
                            {this.state.data.AssigneeId === Exo.UserId ? '' : <Button disabled={this.state.submitting} variant="primary" onClick={this.selfAssign.bind(this)}>Selbst zuweisen</Button>}
                            <Button disabled={this.state.submitting} variant="secondary" onClick={this.toggleAssignUserDialog.bind(this)}>Teammitglied zuweisen</Button>
                        </div>
                    </Col>
                </Row>;

                addUserButton = <Button disabled={this.state.submitting} onClick={this.toggleAddUserDialog.bind(this)} size="sm" variant="secondary">Benutzer hinzufügen</Button>;
            }

            canAnswer = true;
        } else {
            if(Exo.Rank >= 4) {
                closeButton = <Row>
                                <Col>
                                    <Button disabled={this.state.submitting} onClick={this.toggleOpenTicketDialog.bind(this)} variant="danger">Ticket öffnen</Button>
                                    {Exo.Rank >= 7 ? <Button className="ml-1" disabled={this.state.submitting} onClick={this.toggleDeleteTicketDialog.bind(this)} variant="danger">Ticket löschen</Button> : ''}
                                </Col>
                              </Row>;
            }
        }

        return (
            <>
                <ToastContainer />
                <div className="row mb-4">
                    <div className="col-md-12">
                        <span className="h2">{this.state.data.Title}</span>
                        <Link to="/tickets" className="btn btn-primary float-right">Zurück</Link>
                    </div>
                </div>
                <div className="row">
                    <div className="col-md-12">
                        <div className="row">
                            <div className="col-md-8">
                                {this.state.data.settings.display === 0
                                    ? <TicketEntryChat minimal={this.props.minimal} answers={this.state.data.answers} canAnswer={canAnswer} submitting={this.state.submitting} sendMessage={this.send.bind(this)} />
                                    : <TicketEntryThread minimal={this.props.minimal} answers={this.state.data.answers} canAnswer={canAnswer} submitting={this.state.submitting} sendMessage={this.send.bind(this)} /> }
                            </div>
                            <div className="col-md-4">
                                <div className="card">
                                    <div className="card-header">
                                        Details
                                    </div>
                                    <div className="card-body">
                                        <table className="table table-sm">
                                            <tbody>
                                            <tr>
                                                <td>Benutzer</td>
                                                <td>
                                                    {this.state.data.users.map((user, i) => {
                                                        return (
                                                            <div key={user.UserId} style={user.LeftAt !== null ? {'textDecoration': 'line-through'} : {}}>
                                                                <Row>
                                                                    <Col xs='8'>
                                                                        {this.props.minimal == true &&
                                                                        user.Name
                                                                        ||
                                                                        <a href={'/users/' + user.UserId}>{user.Name}</a>
                                                                        } {user.IsAdmin ? <i className="fas fa-user-shield"></i> : ''}
                                                                    </Col>
                                                                    <Col xs='2'>
                                                                        <OverlayTrigger
                                                                            placement="top"
                                                                            overlay={
                                                                                <Tooltip id='tooltip-info'>
                                                                                    <strong>Beigetreten:</strong><br /> {user.JoinedAt}
                                                                                    {user.LeftAt !== null ? <span>
                                                                                    <br /><strong>Verlassen:</strong><br /> {user.LeftAt}
                                                                                    </span> : null}
                                                                                </Tooltip>
                                                                            }
                                                                        >
                                                                        <Button variant="link" style={{color: "white"}} size="sm"><i className="fas fa-info-circle"></i></Button>
                                                                        </OverlayTrigger>
                                                                    </Col>
                                                                    <Col xs='2'>
                                                                        {(user.UserId != this.state.data.UserId && user.LeftAt == null && this.state.data.State === 'Open') ?
                                                                            <Button disabled={this.state.submitting} onClick={this.toggleRemoveUserDialog.bind(this, user.UserId)} variant="link" style={{color: "#d16767"}} size="sm"><i className="fas fa-times-circle"></i></Button>
                                                                        :null}
                                                                    </Col>
                                                                </Row>
                                                            </div>
                                                        );
                                                    })}
                                                    {addUserButton}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Status</td>
                                                <td>{this.state.data.StateText}</td>
                                            </tr>
                                            <tr>
                                                <td>Rang</td>
                                                <td>{this.state.AssignedRank ? this.state.AssignedRank : 1}</td>
                                            </tr>
                                            <tr>
                                                <td>Zugw. Teammitglied</td>
                                                <td>{this.state.data.Assignee ? this.state.data.Assignee : '-'}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        {assignButtons}
                                        {closeButton}
                                        <SelectUserDialog show={this.state.showAddUserDialog} buttonText="hinzufügen" onClosed={this.hideUserDialog.bind(this)} onSelectUser={this.addUser.bind(this)} />
                                        <SelectUserFromListDialog show={this.state.showAssignUserDialog} type={'admin'} id={1} multiple={false} buttonText="zuweisen" onClosed={this.hideAssignUserDialog.bind(this)} onSelectUser={this.assignUser.bind(this)} />
                                        <ConfirmDialog
                                            show={this.state.showRemoveUserDialog}
                                            onClosed={this.hideRemoveUserDialog.bind(this)}
                                            buttonText="Entfernen"
                                            buttonVariant="danger"
                                            title="Benutzer entfernen"
                                            text="Möchtest du den Benutzer entfernen?"
                                            onConfirm={this.removeUser.bind(this)}
                                        />

                                        <ConfirmDialog
                                            show={this.state.showDeleteTicketDialog}
                                            onClosed={this.hideDeleteTicketDialog.bind(this)}
                                            buttonText="Löschen"
                                            buttonVariant="danger"
                                            title="Ticket löschen"
                                            text="Möchtest du das Ticket wirklich löschen? ACHTUNG: Dies kann nicht mehr Rückgängig gemacht werden!"
                                            onConfirm={this.deleteTicket.bind(this)}
                                        />

                                        <ConfirmDialog
                                            show={this.state.showCloseTicketDialog}
                                            onClosed={this.hideCloseTicketDialog.bind(this)}
                                            buttonText="Bestätigen"
                                            buttonCloseText="Abbrechen"
                                            buttonVariant="danger"
                                            title="Ticket schließen"
                                            text="Möchtest du das Ticket wirklich schließen?"
                                            onConfirm={this.close.bind(this)}
                                        />

                                        <ConfirmDialog
                                            show={this.state.showOpenTicketDialog}
                                            onClosed={this.hideOpenTicketDialog.bind(this)}
                                            buttonText="Bestätigen"
                                            buttonCloseText="Abbrechen"
                                            buttonVariant="danger"
                                            title="Ticket öffnen"
                                            text="Möchtest du das Ticket wirklich öffnen?"
                                            onConfirm={this.open.bind(this)}
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </>
        );
    }
}
