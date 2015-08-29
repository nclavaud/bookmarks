var Glyphicon = ReactBootstrap.Glyphicon;
var Nav = ReactBootstrap.Nav;
var NavItem = ReactBootstrap.NavItem;
var Table = ReactBootstrap.Table;
var Input = ReactBootstrap.Input;
var ButtonInput = ReactBootstrap.ButtonInput;

var Page = React.createClass({
    getInitialState: function() {
        return {display: 'table'};
    },
    changeDisplay: function(display) {
        this.setState({display: display});
    },
    handleAddBookmarkSubmit: function(bookmark) {
        $.ajax({
            url: "http://localhost:8080/",
            dataType: "json",
            type: "POST",
            data: bookmark,
            success: function(data) {
                // refresh view
            }.bind(this),
            error: function(xhr, status, err) {
                console.error("Error when adding bookmark", status, err.toString());
            }.bind(this)
        });
    },
    render: function() {
        return (
            <div className="container" style={{marginTop: "1em"}}>
                <Nav bsStyle='pills' activeKey={this.state.display} onSelect={this.changeDisplay}>
                    <NavItem eventKey={'table'} href="#"><Glyphicon glyph="th-list" /></NavItem>
                    <NavItem eventKey={'blocks'} href="#"><Glyphicon glyph="th" /></NavItem>
                </Nav>
                <AddBookmarkForm onAddBookmarkSubmit={this.handleAddBookmarkSubmit} />
                <div style={{"marginTop": "1em"}}>
                    <CoverList url="http://localhost:8080/" display={this.state.display} />
                </div>
            </div>
        );
    }
});

var AddBookmarkForm = React.createClass({
    handleSubmit: function(e) {
        e.preventDefault();

        var url = this.refs.url.getValue().trim();
        if (!url) {
            return;
        }

        this.props.onAddBookmarkSubmit({url: url});

        this.refs.url.getInputDOMNode().value = "";
        return;
    },
    render: function() {
        return (
            <form onSubmit={this.handleSubmit}>
                <Input type="text" placeholder="Enter an URL to bookmark" ref="url" />
                <ButtonInput type="submit" value="Add" />
            </form>
        );
    }
});

var CoverList = React.createClass({
    loadData: function() {
        $.ajax({
            url: this.props.url,
            dataType: 'json',
            cache: false,
            success: function(data) {
                this.setState({resources: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },
    componentDidMount: function() {
        this.loadData();
    },
    getInitialState: function() {
        return {resources: []};
    },
    render: function() {
        if ('blocks' == this.props.display) {
            var covers = this.state.resources.map(function (cover) {
                return <Cover key={cover.uuid} type={cover.type} title={cover.title} image={cover.image} url={cover.url} />
            });

            return (
                <div>{covers}</div>
            );
        }

        var rows = this.state.resources.map(function (cover) {
            return <CoverAsTableRow key={cover.uuid} type={cover.type} title={cover.title} image={cover.image} url={cover.url} />
        });

        return (
            <Table striped bordered condensed><tbody>{rows}</tbody></Table>
        );
    }
});

var Cover = React.createClass({
    render: function() {
        var style = {
            backgroundImage: 'url(' + this.props.image + ')'
        };
        return (
            <Link href={this.props.url}>
                <div className="cover" style={style}>
                    <div className="title">{this.props.title}</div>
                    <div className="type">{this.props.type}</div>
                </div>
            </Link>
        );
    }
});

var CoverAsTableRow = React.createClass({
    render: function() {
        return (
            <tr>
                <td>
                    <Link href={this.props.url}>{this.props.title}</Link>
                </td>
                <td>
                    {this.props.type}
                </td>
            </tr>
        );
    }
});

var Link = React.createClass({
    render: function() {
        return (
            <a href={this.props.href} target="_blank">{this.props.children}</a>
        );
    }
});

React.render(
    <Page />,
    document.getElementById('a')
);
