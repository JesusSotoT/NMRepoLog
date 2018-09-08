package regiHora;
import java.sql.Connection;
import java.sql.DatabaseMetaData;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.swing.JOptionPane;

public class Conexion{
	Connection conexion;
	Statement sentencia;
        Connection conn=null;
	public String usuario="nmdevel", password="nmdevel", iP="nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com", puerto="3306", nombreBD="nmdev";
//        public String usuario="postgres", password="cdm", iP="localhost", puerto="5432", nombreBD="CDM";

	public Conexion(){
		this.usuario = usuario;
		this.password = password;
		this.iP = iP;
		this.puerto = puerto;
		this.nombreBD = nombreBD;
	}

    public Connection conectar(){
        try{
            Class.forName("com.mysql.jdbc.Driver");
           // conexion=DriverManager.getConnection(ruta,user,pass);
           // Class.forName("org.postgresql.Driver");
            conn = DriverManager.getConnection("jdbc:mysql://nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com:3306/nmdev","nmdevel","nmdevel");
            if (conn!=null){
            System.out.println("Conección a base de datos listo...");
            }
            else if (conn==null)
            {
            throw new SQLException();
            }
        }catch(SQLException e){
            JOptionPane.showMessageDialog(null, e.getMessage());
        } catch (ClassNotFoundException | NullPointerException e) {
            JOptionPane.showMessageDialog(null, "NO SE PUDO CONECTAR A LA BASE DE DATOS DE POSTGRESQL\n"+e.getMessage());
        }finally{
        return conn;
        }
}
public void desconectar(){
    conn = null;
    System.out.println("Desconexion a base de datos listo...");
}
        
	// sw es true si va a conectar con MySQL (3306) y postgresql (5432)
	// sw es false si va a conectar con access porque es por medio del odbc
	private void conectar	(String driver, String puente, boolean sw) throws ClassNotFoundException, SQLException, InstantiationException, IllegalAccessException{

		Class.forName(driver).newInstance();
		if (sw)
 			conexion = DriverManager.getConnection 
                ("jdbc:"+puente+"://"+iP+":"+puerto+"/"+nombreBD,usuario,password);
		else
			conexion = DriverManager.getConnection ("jdbc:"+puente+
								":"+nombreBD,usuario,password);
		sentencia = conexion.createStatement (ResultSet.TYPE_SCROLL_SENSITIVE,
								    ResultSet.CONCUR_UPDATABLE);
	}

	// Con JDBC
	public void ConexionJDBC() throws 	ClassNotFoundException, 
							SQLException,
							InstantiationException,
							IllegalAccessException{
		conectar("sun.jdbc.odbc.JdbcOdbcDriver","odbc",false);
	}

	// Con MySQL
	public void ConexionMySQL() throws   ClassNotFoundException,
							SQLException,
							InstantiationException,
							IllegalAccessException{
		conectar("com.mysql.jdbc.Driver","mysql",true);
	}

	// Con PostgreSql
	public void ConexionPostgres() throws ClassNotFoundException,
							SQLException,
							InstantiationException,
							IllegalAccessException{
		conectar("org.postgresql.Driver","postgresql",true);
	}

	public void actualizar(String actualiza) throws SQLException{
		sentencia.executeUpdate(actualiza);
	}
        
        public int SIactualiza(String actualiza){
            int n = 0;
            try {
                n = sentencia.executeUpdate(actualiza);
                System.out.println(actualiza);
            } catch (SQLException ex) {
                Logger.getLogger(Conexion.class.getName()).log(Level.SEVERE, null, ex);
                JOptionPane.showMessageDialog(null, "ERROR AL ACTUALIZAR\n"+ex);
            }
            return n;
	}

	public ResultSet consultar(String consulta) throws SQLException{
		return (sentencia.executeQuery(consulta));
	}

	// Devuelve el numero de filas de la tabla virtual
	public int contar(ResultSet rS) throws SQLException{
		int cont = 0;
		rS.beforeFirst();
		while (rS.next()) cont++;
		return (cont);
	}

	public void cerrar() throws SQLException{
		conexion.close();
		sentencia.close();
	}
        
        public DatabaseMetaData getMetaData() throws SQLException
            {
                return conexion.getMetaData();
            }
}
