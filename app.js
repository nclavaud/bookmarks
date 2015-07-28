var Glyphicon = ReactBootstrap.Glyphicon;
var Nav = ReactBootstrap.Nav;
var NavItem = ReactBootstrap.NavItem;
var Table = ReactBootstrap.Table;

var Page = React.createClass({
    getInitialState: function() {
        return {display: 'table'};
    },
    changeDisplay: function(display) {
        this.setState({display: display});
    },
    render: function() {
        return (
            <div className="container" style={{marginTop: "1em"}}>
                <Nav bsStyle='pills' activeKey={this.state.display} onSelect={this.changeDisplay}>
                    <NavItem eventKey={'table'} href="#"><Glyphicon glyph="th-list" /></NavItem>
                    <NavItem eventKey={'blocks'} href="#"><Glyphicon glyph="th" /></NavItem>
                </Nav>
                <div style={{"marginTop": "1em"}}>
                    <CoverList url="resources.json" display={this.state.display} />
                </div>
            </div>
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
            <a href={this.props.url} target="_blank">
                <div className="cover" style={style}>
                    <div className="title">{this.props.title}</div>
                    <div className="type">{this.props.type}</div>
                </div>
            </a>
        );
    }
});

var CoverAsTableRow = React.createClass({
    render: function() {
        return (
            <tr>
                <td>
                    <a href={this.props.url} target="_blank">{this.props.title}</a>
                </td>
                <td>
                    {this.props.type}
                </td>
            </tr>
        );
    }
});

React.render(
    <Page />,
    document.getElementById('a')
);
