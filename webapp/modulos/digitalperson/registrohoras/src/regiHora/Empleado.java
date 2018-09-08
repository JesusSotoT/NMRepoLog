package regiHora;

import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.swing.JComboBox;
import javax.swing.JOptionPane;

/**
 *
 * @author CORE I5
 */
public class Empleado {

    private String idEmpleado;
   // private String nombre;
   
    
    public Empleado(String idEmpleado) {
       
        //this.nombre = nombre;
        this.idEmpleado = idEmpleado;
      
    }
    
    public Empleado(){
        
    }
    
 
//
//    public String getNombre() {
//        return nombre;
//    }
//
//    public void setNombre(String nombre) {
//        this.nombre = nombre;
//    }

    public String getIdEmpleado() {
        return idEmpleado;
    }

    public void setIdEmpleado(String apellido) {
        this.idEmpleado = apellido;
    }

       
    public void mostrarClientes(JComboBox<Empleado> comboCliente){
        try {
            Conexion conn = new Conexion();
            conn.ConexionMySQL();
            ResultSet rs = conn.consultar("SELECT idEmpleado,codigo,apellidoPaterno,apellidoMaterno,nombreEmpleado FROM nomi_empleados ORDER BY nombreEmpleado ASC");
            while(rs.next()){
                comboCliente.addItem(new Empleado(
                              //  rs.getString("nombreEmpleado"),
                               rs.getString("idEmpleado")
                       // rs.getInt("idEmpleado")
                               
                        )
                );
            }
        } catch (Exception ex) {
            Logger.getLogger(Empleado.class.getName()).log(Level.SEVERE, null, ex);
            JOptionPane.showMessageDialog(null, "ERROR AL MOSTRAR LOS CLIENTES");
        }
    }
    
    @Override
    public String toString(){
       // return nombre+" "+idEmpleado;
           return idEmpleado;
    }
    
}
